<?php

namespace StackSite\UserManagement;

use StackSite\Core\SqlHandler;

class UserPersistence
{
    private User $user;

    public function upload(): int
    {
        $query =
            "INSERT INTO users (
                   username,
                   email,
                   password_hash,
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

    public function fetchByUserId(int $userId): ?User
    {
        $query = "SELECT * FROM users
                    WHERE userId = '" . SqlHandler::cleanString($userId) . "' 
                    LIMIT 1";

        $result = SqlHandler::fetch($query);

        return !empty($result) ? UserFactory::fromArray($result[0]) : null;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
