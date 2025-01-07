<?php

namespace StackSite\UserManagement;

class UserFactory
{
    public static function fromArray(array $data): User
    {
        return new User(
            $data['id'] ?? null,
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['createdAt'] ?? null,
            $data['modifiedAt'] ?? null
        );
    }

}