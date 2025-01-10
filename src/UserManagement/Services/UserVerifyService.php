<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Services;

use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\UserPersistence;

readonly class UserVerifyService
{
    public function __construct(
        private TokenPersistence $tokenPersistence,
        private UserPersistence  $userPersistence
    ) {
    }

    public function verifyUser(Token $token): bool
    {
        if ($token->getToken() === '') {
            return false;
        }

        $token = $this->tokenPersistence->fetchByTokenAndType($token);
        if ($token === null) {
            return false;
        }

        $user = $this->userPersistence->fetchByUserId($token->getUserId());
        if ($user === null) {
            return false;
        }

        $user->setConfirmed(true);
        $this->userPersistence->setUser($user);
        if ($this->userPersistence->confirmUser() === false) {
            return false;
        }

        if ($this->tokenPersistence->deleteById($token->getId()) === false) {
            return false;
        }

        return true;
    }
}