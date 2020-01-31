<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:31
 */

namespace FoodConfig\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use FoodConfig\Form\LoginForm;
use FoodConfig\Form\RegisterForm;
use FoodConfig\Entity\User;

class VerifyController extends AbstractActionController
{
    protected $authManager;
    protected $entityManager;
    protected $recapchaKey;
    protected $mailService;
    function __construct($authManager, $entityManager, $recapchaKey, $mailService)
    {
        $this->authManager = $authManager;
        $this->entityManager = $entityManager;
        $this->recapchaKey = $recapchaKey;
        $this->mailService = $mailService;
    }
    public function indexAction()
    {
        $this->redirect()->toRoute('profile', [
            'action' => 'profile']);
    }

    public function loginAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $request->getPost()->toArray();
            $form->setData($data);
            // Validate form
            if($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                $result = $this->authManager->login($data['email'],
                    sha1('food'.$data['password'].'config'),
                    $data['remember_me']);
                switch ($result->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        /** do stuff for nonexistent identity **/
                        $this->flashMessenger()->addErrorMessage(current($result->getMessages()));
                        $this->redirect()->toRoute('foodconfig/verify', [
                            'action' => 'login'
                        ]);
                        break;
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        /** do stuff for invalid credential **/
                        $this->flashMessenger()->addErrorMessage(current($result->getMessages()));
                        $this->redirect()->toRoute('foodconfig/verify', [
                            'action' => 'login'
                        ]);
                        break;
                    case Result::SUCCESS:
                        /** do stuff for successful authentication **/
                        $this->flashMessenger()->addSuccessMessage(current($result->getMessages()));
                        $this->redirect()->toRoute('home');
                        break;
                    default:
                        /** do stuff for other failure **/
                        echo current($result->getMessages());
                        break;
                }
            }
        }
        return new ViewModel([
            'form' => $form,
            'data' => $data,
        ]);
    }

    public function registerAction()
    {
        $form = new RegisterForm();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $request->getPost()->toArray();
            $form->setData($data);
            // Validate form
            if($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                // check if email taken 
                if($this->authManager->checkIfEmailExists($data['email']))
                    $data['error'] = 'Email jest zajęty';
                else {

                    $hashedPassword = sha1('food'.$data['password_one'].'config');

                    $ext = '.' . strtolower(pathinfo($this->params()->fromFiles('avatar')['name'], PATHINFO_EXTENSION));

                    $name = date("Y_m_d") . "_" . rand(10000, 99999) . $ext;
                    $path = '/img/avatar/' . $name;

                    if (strpos($name, '.jpg') OR strpos($name, '.png')) {
                        move_uploaded_file($this->params()->fromFiles('avatar')['tmp_name'], PUBLIC_PATH . $path);
                    }

                    if (sha1('food'.$data['password_two'].'config') == $hashedPassword) {
                        $result = $this->authManager->register($data['name'], $data['surname'], $data['email'], $hashedPassword, $data['usershow'], $name);
                        if($result) {
                            $this->flashMessenger()->addSuccessMessage('Konto utworzone!');
                            $this->redirect()->toRoute('foodconfig/verify', [
                                'action' => 'login'
                            ]);
                        }
                        else $data['error'] = 'Wystąpił błąd podczas rejestracji. Spróbuj ponownie ...';
                    } else
                    {
                        $data['error'] = 'Hasła się nie zgadzają';
                    }

                    unset($data['password_one']);
                    unset($data['password_two']);
                }

                return new ViewModel([
                    'form' => $form,
                    'test' => $data
                ]);

            }
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function logoutAction()
    {
        $this->authManager->logout();
        $this->flashMessenger()->addSuccessMessage('Zostałeś wylogowany!');
        $this->redirect()->toRoute('foodconfig/verify', [
            'action' => 'login'
        ]);
        return [];
    }

    public function forgotAction()
    {
        // new form
        $form = new \FoodConfig\Form\ForgotPasswordForm($this->entityManager, $this->recapchaKey);
        // request
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setData($data);

            if ($form->isValid()) {

                $data = $form->getData();
                $userRepository = $this->entityManager->getRepository(User::class);
                $user = $userRepository->findOneBy(['email' => $data['email']]);
                $token = sha1('f'.$user->getPassword().'c');
                $email = $user->getEmail();
                $u_name = $user->getName() . ' ' . $user->getSurname();
                $link = $_SERVER['SERVER_NAME']
                    . $this->url()->fromRoute('foodconfig/verify', ['action' => 'active'])
                    . "/email/$email/token/$token";
                // mailtemplate
                $content = [
                    'u_name'  => $u_name,
                    'link'     => $link,
                ];
                // send email through event manager
                $this->getEventManager()->trigger('send_mail', $this, $content);
                $this->flashMessenger()->addSuccessMessage('E-mail wysłany!');
                return $this->redirect()->toRoute('foodconfig/verify', [
                    'action' => 'forgot'
                ]);
            }
        }

        return new ViewModel([
            'form'		=> $form
        ]);
    }

    public function activeAction()
    {

        // get params from route
        $email = $this->params()->fromRoute('email');
        $token = $this->params()->fromRoute('token');
        if (null === $email || null === $token) {
            return $this->redirect()->toRoute('foodconfig/verify', [
                'action' => 'login'
            ]);
        }

        // get user by email
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);
        $myToken = sha1('f'.$user->getPassword().'c');
        if ($token !== $myToken) {
            return $this->redirect()->toRoute('foodconfig/verify', [
                'action' => 'login'
            ]);
        }
        // form change password
        $form = new \FoodConfig\Form\ChangePasswordForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form = new \FoodConfig\Form\ChangePasswordForm();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();

                $user->setPassword(sha1('food'.$data['password'].'config'));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Hasło pomyślnie zmienione!');
                return $this->redirect()->toRoute('foodconfig/verify', [
                    'action' => 'login'
                ]);
            }
        }
        return new ViewModel([
            'form' => $form,
            'email' => $email,
            'token' => $token
        ]);
    }

    public function deniedAction()
    {
        return new ViewModel();
    }
}