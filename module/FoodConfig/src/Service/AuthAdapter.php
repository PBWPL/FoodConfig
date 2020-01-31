<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:50
 */

namespace FoodConfig\Service;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use FoodConfig\Entity\User;

class AuthAdapter extends AbstractAdapter
{
    protected $entityManager;
    function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function authenticate()
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $this->getIdentity()]);
        // If there is no such user, return 'Identity Not Found' status.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Błędne dane!']);
        }

        // If the user with such email exists, we need to check if it is active or retired.
        // Do not allow retired users to log in.
        if ($user->getActive() != User::ACTIVE) {
            return new Result(
                Result::FAILURE,
                null,
                ['Błędne dane!']);
        }

        // Now we need to calculate hash based on user-entered password and compare
        // it with the password hash stored in database.
        $passwordHash = $user->getPassword();

        if ($this->getCredential() == $passwordHash) {
            // Great! The password hash matches. Return user identity (username) to be
            // saved in session for later use.
            return new Result(
                Result::SUCCESS,
                [
                    'id'        => $user->getId(),
                    'username'  => $user->getName(),
                    'level'     => $user->getLevel()
                ],
                ['Logowanie zakończyło się pomyślnie.']);
        }

        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Błędne dane!']);
    }
}