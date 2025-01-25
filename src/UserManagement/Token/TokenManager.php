<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

readonly class TokenManager
{
    public function __construct(
        private TokenPersistence $tokenPersistence,
        private TokenValidator   $tokenValidator
    ) {
    }

    public function checkDuplicateAndExpireToken(Token $token): bool
    {
        $fetchedToken = $this->tokenPersistence->fetchByUserIdAndType($token->getUserId(), $token->getType());

        if ($fetchedToken === null) {
            return false;
        }

        if ($this->tokenValidator->isExpired($fetchedToken)) {
            $this->tokenPersistence->deleteById($fetchedToken->getId());
            return false;
        }

        return true;
    }
}