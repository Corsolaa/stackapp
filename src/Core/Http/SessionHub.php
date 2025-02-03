<?php

declare(strict_types=1);

namespace StackSite\Core\Http;

class SessionHub
{
    public static function loginUser(string $token): void
    {
        $_SESSION['token'] = $token;
    }

    public static function logout(): void
    {
        unset($_SESSION['token']);
    }

    public static function getToken(): ?string
    {
        return $_SESSION['token'] ?? null;
    }
}