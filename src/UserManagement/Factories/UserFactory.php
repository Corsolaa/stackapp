<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Factories;

use StackSite\UserManagement\User;

class UserFactory
{
    public static function fromArray(array $data): User
    {
        return new User(
            isset($data['id']) ? (int)$data['id'] : null,
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            isset($data['confirmed']) && $data['confirmed'],
            $data['createdAt'] ?? null,
            $data['modifiedAt'] ?? null
        );

    }

}