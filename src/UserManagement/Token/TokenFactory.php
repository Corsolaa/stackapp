<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

use Random\RandomException;
use StackSite\Core\Http\SessionHub;

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

    public function generateLoginUser(int $userId): ?Token
    {
        try {
            return new Token(
                null,
                $userId,
                bin2hex(random_bytes(16)),
                TOKEN::LOGIN,
                (time() + (90 * 24 * 60 * 60))
            );
        } catch (RandomException) {
            return null;
        }
    }

    public static function generateTokenFromLoginSession(): Token
    {
        return new Token(token: SessionHub::getToken(), type:Token::LOGIN);
    }

    public function fromArray(array $data): Token
    {
        return new Token(
            isset($data['id']) ? (int)$data['id'] : null,
            isset($data['user_id']) ? (int)$data['user_id'] : null,
            $data['token'] ?? '',
            $data['type'] ?? '',
            isset($data['expires_at']) ? (int)$data['expires_at'] : null,
            isset($data['created_at']) ? (int)$data['created_at'] : null
        );
    }
}