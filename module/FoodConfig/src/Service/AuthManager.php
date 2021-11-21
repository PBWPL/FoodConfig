<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:50
 */

namespace FoodConfig\Service;

use FoodConfig\Entity\Role;
use FoodConfig\Entity\User;
use Laminas\Authentication\Result;

class AuthManager
{
    protected $authService;
    protected $sessionManager;
    protected $entityManager;
    function __construct($authService, $sessionManager, $entityManager)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->entityManager = $entityManager;
    }
    public function checkIdentity()
    {
        if ($this->authService->hasIdentity()) {
            $user = $this->authService->getIdentity();
            return $user;
        }
        return false;
    }

    public function getUser() {
        if ($this->authService->hasIdentity()) {
            $userRepository = $this->entityManager->getRepository(User::class);
            return $userRepository->findOneBy(['id' => $this->authService->getIdentity()['id']]);
        }

        return false;
    }

    public function getUserAccess()
    {
        if ($this->authService->hasIdentity()) {
            $user = $this->authService->getIdentity();
            $roleRepository = $this->entityManager->getRepository(Role::class);
            $role = $roleRepository->findOneBy(['id' => $user['level']]);
            $data = [
                'username' => $user['username'],
                'role' => $role->getName(),
                'access' => json_decode($role->getAccess(), true)
            ];
            return $data;
        }
    }
    public function login($username, $password, $remember = 0)
    {
        if ($this->authService->hasIdentity()) {
            $this->authService->clearIdentity();
        }

        $this->authService->getAdapter()
            ->setIdentity($username)
            ->setCredential($password);
        $result = $this->authService->authenticate();
        if ($result->getCode() == Result::SUCCESS && $remember == 1) {
            $this->sessionManager->rememberMe(60*60*24*30);
        }
        return $result;
    }

    public function checkIfEmailExists($email) {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);
        if($user) return true;
        return false;
    }

    public function register($name, $surname, $email, $password_one, $usershow, $avatar)
    {
        try {

            $user = new User();
            $user->setPassword($password_one);
            $user->setEmail($email);
            $user->setName($name);
            $user->setSurname($surname);
            $user->setUsershow($usershow);
            if (strpos($avatar, '.jpg') OR strpos($avatar, '.png')) {
                $user->setAvatar($avatar);
            }
            $date = date("Y-m-d H:i:s");
            $user->setCreateTime($date);

            $roleRepository = $this->entityManager->getRepository(Role::class);
            $role = $roleRepository->findOneBy(['id' => 1]);
            $user->setRole($role);

            // Add the entity to entity manager.
            $this->entityManager->persist($user);
                
            // Apply changes to database.
            $this->entityManager->flush();
            return true;
        } catch(Exception $e) {
            echo $e->__toString();
            return false;
        }
    }

    public function logout()
    {
        if ($this->authService->hasIdentity()) {
            $this->authService->clearIdentity();
        }
    }
}