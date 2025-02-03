<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

class TokenValidator
{
    public function isExpired(Token $token): bool
    {
        return $token->getExpiresAt() < time();
    }
}