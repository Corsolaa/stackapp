<?php

declare(strict_types=1);

namespace StackSite\UserManagement;

use StackSite\Core\SqlHandler;
use StackSite\UserManagement\Factories\UserFactory;

class UserPersistence
{
    private User $user;

    public function upload(): int
    {
        $query =
            "INSERT INTO users (
                   username,
                   email,
                   password,
                   created_at
                   )
            VALUES (
                    '" . SqlHandler::cleanString($this->user->getUsername()) . "',
                    '" . SqlHandler::cleanString($this->user->getEmail()) . "',
                    '" . SqlHandler::cleanString($this->user->getPassword()) . "',
                    " . time() . "
                    )";

        return SqlHandler::insert($query);
    }

    public function fetchAll(): array
    {
        $query  = "SELECT * FROM users";
        $result = SqlHandler::fetch($query);

        $users = [];

        foreach ($result as $row) {
            $users[] = UserFactory::fromArray($row);
        }

        return $users;
    }

    public function fetchByUsername(string $username): ?User
    {
        $query = "SELECT * FROM users
                    WHERE username = '" . SqlHandler::cleanString($username) . "' 
                    LIMIT 1";

        $result = SqlHandler::fetch($query);

        return !empty($result) ? UserFactory::fromArray($result[0]) : null;
    }

    public function fetchByEmail(string $email): ?User
    {
        $query = "SELECT * FROM users
                    WHERE email = '" . SqlHandler::cleanString($email) . "' 
                    LIMIT 1";

        $result = SqlHandler::fetch($query);

        return !empty($result) ? UserFactory::fromArray($result[0]) : null;
    }

    public function fetchByUserId(int $user_id): ?User
    {
        $query = "SELECT * FROM users
                    WHERE id = '" . $user_id . "' 
                    LIMIT 1";

        $result = SqlHandler::fetch($query);

        return !empty($result) ? UserFactory::fromArray($result[0]) : null;
    }

    public function confirmUser(): bool
    {
        $query = "UPDATE users SET confirmed = 1 WHERE id = " . $this->user->getId();
        $result = SqlHandler::update($query);

        return $result > 0;
    }

    public function updatePassword(): bool
    {
        $query = "UPDATE users SET password = " . SqlHandler::cleanString($this->user->getPassword()) .
            " WHERE id = " . $this->user->getId();
        $result = SqlHandler::update($query);

        return $result > 0;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
