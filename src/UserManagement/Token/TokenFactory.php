<?php

namespace StackSite\UserManagement\Token;

use Random\RandomException;

class TokenFactory
{
    public function generateVerify(int $userId): ?Token
    {
        try {
            return new Token(
                null,
                $userId,
                bin2hex(random_bytes(16)),
                TOKEN::VERIFY
            );
        } catch (RandomException) {
            return null;
        }
    }

    public function generatePasswordReset(int $userId): ?Token
    {
        try {
            return new Token(
                null,
                $userId,
                bin2hex(random_bytes(16)),
                TOKEN::PASSWORD,
                (time() + (60 * 15))
            );
        } catch (RandomException) {
            return null;
        }
    }
}