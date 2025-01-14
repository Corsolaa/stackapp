<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Services;

use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\Token\UserTokenService;
use StackSite\UserManagement\UserPersistence;

readonly class UserVerifyService
{
    public function __construct(
        private TokenPersistence $tokenPersistence,
        private UserPersistence  $userPersistence,
        private UserTokenService $userTokenService
    ) {
    }

    public function verifyUser(Token $verifyToken): bool
    {
        if ($verifyToken->getToken() === '') {
            return false;
        }

        $verifyToken = $this->tokenPersistence->fetchByTokenAndType($verifyToken);
        if ($verifyToken === null) {
            return false;
        }

        $user = $this->userPersistence->fetchByUserId($verifyToken->getUserId());
        if ($user === null) {
            return false;
        }

        $user->setConfirmed(true);
        $this->userPersistence->setUser($user);
        if ($this->userPersistence->confirmUser() === false) {
            return false;
        }

        return $this->userTokenService->processUserConfirmToken($user, $verifyToken);
    }
}