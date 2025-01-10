<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Factories;

use StackSite\UserManagement\User;

class UserFactory
{
    public static function fromArray(array $data): User
    {
        return new User(
            (int)$data['id'] ?? null,
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            (bool)$data['confirmed'] ?? false,
            $data['createdAt'] ?? null,
            $data['modifiedAt'] ?? null
        );
    }

}