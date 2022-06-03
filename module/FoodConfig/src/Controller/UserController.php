<?php

/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:31
 */

namespace FoodConfig\Controller;

use FoodConfig\Entity\Dish;
use FoodConfig\Entity\User;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use FoodConfig\Entity\User_Enjoy_Dish;
use FoodConfig\Form\UserForm;
use Throwable;

class UserController extends AbstractActionController
{

    protected $aclConfig;
    protected $entityManager;
    protected $authManager;
    protected $mailService;
    function __construct($aclConfig, $mailService, $entityManager, $authManager)
    {
        $this->aclConfig = $aclConfig;
        $this->mailService = $mailService;
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
    }

    public function indexAction()
    {
        $user = $this->authManager->getUser();
        $em = $this->entityManager;

        if ($user->getLevel() == 1) {
            $users = $em->getRepository(User::class)->findBy(['usershow' => 1]);
        } else {
            $users = $em->getRepository(User::class)->findAll();
        }

        return new ViewModel([
            'user' => $users,
            'user_1' => $user
        ]);
    }

    public function profileAction()
    {
        $user = $this->authManager->getUser();
        $em = $this->entityManager;

        $user_enjoy_dish = $em->createQuery(
            "SELECT u FROM FoodConfig\Entity\User_Enjoy_Dish u WHERE u.user_id = 3"
        );


        return new ViewModel([
            'user' => $user,
            'user_enjoy_dish' => $user_enjoy_dish
        ]);
    }

    /**
     * Create user
     */
    public function createAction()
    {
        $form = new UserForm($this->aclConfig);

        return new ViewModel([
            'acl_config'    => $this->aclConfig,
            'form'            => $form
        ]);
    }

    public function storeAction()
    {
        $form = new UserForm($this->aclConfig);
        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                $formData = $form->getData();
                $user = new \FoodConfig\Entity\User();
                $user->setName($formData['name']);
                $user->setEmail($formData['email']);
                foreach ($formData as $key => $val) {
                    if (!is_array($val)) {
                        unset($formData[$key]);
                    }
                }
                $name = json_encode($formData);
                $user->setName($name);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $idUser = $user->getId();
                if ($idUser) {
                    $this->flashMessenger()->addSuccessMessage('Użytkownik pomyślnie utworzony!');
                    $this->redirect()->toRoute('foodconfig/user', [
                        'action' => 'edit',
                        'id' => $idUser,
                    ]);
                }
            } else {
                $view = new ViewModel([
                    'form'            => $form,
                    'acl_config'    => $this->aclConfig
                ]);
                $view->setTemplate('foodconfig/user/create');
                return $view;
            }
        }
        return $this->getResponse();
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('foodconfig/user');
        }
        $form = new UserForm($this->aclConfig);
        try {
            $user = $this->entityManager->getRepository(User::class)
                ->findOneBy(['id' => $id]);
            if (null === $user) {
                return $this->redirect()->toRoute('foodconfig/user');
            }
            $data = [
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail(),
                'level' => $user->getLevel(),
                'active' => $user->getActive(),
                'password' => $user->getPassword(),
                'avatar' => $user->getAvatar(),
                'usershow' => $user->getUsershow(),

            ];

            $form->setData($data);
        } catch (Throwable $e) {
            return $this->redirect()->toRoute('foodconfig/user');
        }
        return new ViewModel([
            'id'            => $id,
            'form'            => $form,
        ]);
    }

    public function updateAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('foodconfig/user');
        }
        // call form
        $form = new UserForm($this->aclConfig);

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                $formData = $form->getData();
                $user = $this->entityManager->getRepository(User::class)
                    ->findOneBy(['id' => $id]);
                if (null === $user) {
                    return $this->redirect()->toRoute('foodconfig/user');
                }
                $user->setName($formData['name']);
                $user->setSurname($formData['surname']);
                $user->setAvatar($formData['avatar']);
                $user->setActive($formData['active']);
                $user->setPassword(sha1('food' . $formData['password'] . 'config'));
                $user->setLevel($formData['level']);
                $user->setUsershow($formData['usershow']);
                $user->setEmail($formData['email']);
                $date = date("Y-m-d H:i:s");
                $user->setCreateTime($date);
                foreach ($formData as $key => $val) {
                    if (!is_array($val)) {
                        unset($formData[$key]);
                    }
                }
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Edycja użytkownika zakończona sukcesem!');
                $this->redirect()->toRoute('foodconfig/user', [
                    'action' => 'edit',
                    'acl_config'     => $this->aclConfig,
                    'id' => $id,
                ]);
            } else {
                $view = new ViewModel([
                    'form'            => $form,
                    'acl_config'    => $this->aclConfig
                ]);
                $view->setTemplate('foodconfig/user/create');
                return $view;
            }
        }
        return $this->getResponse();
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('foodconfig/user');
        }
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $id]);
        if (!$user) {
            return $this->redirect()->toRoute('foodconfig/user');
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('Użytkownik został usunięty!');
        $this->redirect()->toRoute('foodconfig/user');
    }

    public function likeAction()
    {
        $user = $this->authManager->checkIdentity();

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            try {
                $pid = $this->params()->fromRoute('id', -1);

                $user_enjoy_dish = new User_Enjoy_Dish();
                $user_enjoy_dish->setUserId($user['id']);
                $user_enjoy_dish->setDishId($pid);

                $this->entityManager->persist($user_enjoy_dish);
                $this->entityManager->flush();

                return new JsonModel([
                    'status' => 'SUCCESS'
                ]);
            } catch (Throwable $e) {
                return new JsonModel([
                    'status' => 'ERROR',
                    'message' => ""
                ]);
            }
        } else {
            return new JsonModel([
                'status' => 'ERROR',
                'message' => "Incorrect request"
            ]);
        }
    }

    public function unlikeAction()
    {
        $user = $this->authManager->checkIdentity();

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            try {
                $pid = $this->params()->fromRoute('id', -1);

                $user_enjoy_dish = $this->entityManager->getRepository(User_Enjoy_Dish::class)
                    ->findOneBy([
                        'dish_id' => $pid,
                        'user_id' => $user['id']
                    ]);

                $this->entityManager->remove($user_enjoy_dish);
                $this->entityManager->flush();

                return new JsonModel([
                    'status' => 'SUCCESS'
                ]);
            } catch (Throwable $e) {
                return new JsonModel([
                    'status' => 'ERROR',
                    'message' => ''
                ]);
            }
        } else {
            return new JsonModel([
                'status' => 'ERROR',
                'message' => 'Incorrect request'
            ]);
        }
    }

    public function shoppinglistAction()
    {
        $user = $this->authManager->checkIdentity();

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            try {

                $u_id = $user['id'];

                $email = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $u_id]);
                $email = $email->getEmail(); // email
                $pid = $this->params()->fromRoute('id', -1);
                $dish = $this->entityManager->getRepository(Dish::class)->findOneBy(['id' => $pid]);

                $name = $dish->getName();

                $shoppinglist = []; // shopping list
                foreach ($dish->getIngredient() as $item => $val) {
                    array_push($shoppinglist, [
                        'name' => $val->getName(),
                        'count' => $val->getCount()
                    ]);
                }

                // mailtemplate
                $content = [
                    'email'  => $email,
                    'shoppinglist'     => $shoppinglist,
                    'name' => $name,
                ];
                // send email through event manager
                $this->getEventManager()->trigger('send_mail_2', $this, $content);

                return new JsonModel([
                    'status' => 'SUCCESS'
                ]);
            } catch (Throwable $e) {
                return new JsonModel([
                    'status' => 'ERROR',
                    'message' => ''
                ]);
            }
        } else {
            return new JsonModel([
                'status' => 'ERROR',
                'message' => 'Incorrect request'
            ]);
        }
    }
}
