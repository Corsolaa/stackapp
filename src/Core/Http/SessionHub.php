<?php

declare(strict_types=1);

namespace StackSite\Core\Http;

class SessionHub
{
    public static function loginUser(int $user_id): void
    {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id']   = $user_id;
    }

    public static function logout(): void
    {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_id']);
    }
}