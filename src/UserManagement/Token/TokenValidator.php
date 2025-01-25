<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

class TokenValidator
{
    public function isExpired(Token $token): bool
    {
        if ($token->getExpiresAt() < time()) {
            return true;
        }
        return false;
    }
}