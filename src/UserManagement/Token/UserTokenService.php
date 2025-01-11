<?php

namespace StackSite\UserManagement\Token;

use StackSite\UserManagement\Token\Mailing\TokenMailingServiceInterface;
use StackSite\UserManagement\User;

readonly class UserTokenService
{
    public function __construct(
        private TokenPersistence             $tokenPersistence,
        private TokenMailingServiceInterface $tokenMailingService,
    ) {
    }

    public function processUserVerifyToken(User $user, Token $token): bool
    {
        $this->tokenPersistence
            ->setToken($token)
            ->upload();

        return $this->tokenMailingService->send($token, $user->getEmail());
    }

    public function processUserLoginToken(Token $token): bool
    {
        $result = $this->tokenPersistence->setToken($token)
            ->upload();
        return $result > 0;
    }
}