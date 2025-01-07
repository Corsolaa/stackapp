<?php

namespace StackSite\UserManagement;

class User
{
    public function __construct(
        private ?int $id = null,
        private string $username = '',
        private string $email = '',
        private string $password = '',
        private ?int $createdAt = null,
        private ?int $modifiedAt = null
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function getModifiedAt(): ?int
    {
        return $this->modifiedAt;
    }

    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function sanitizeUsername(): void
    {
        $this->username = strtolower($this->username);
        $this->username = trim($this->username);
        $this->username = htmlspecialchars($this->username, ENT_QUOTES, 'UTF-8');
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($this->password, $password);
    }
}
