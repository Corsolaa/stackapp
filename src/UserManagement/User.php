<?php

declare(strict_types=1);

namespace StackSite\UserManagement;

class User
{
    public function __construct(
        private ?int            $id = null,
        private string          $username = '',
        private readonly string $email = '',
        private string          $password = '',
        private bool            $confirmed = false,
        private readonly ?int   $createdAt = null,
        private readonly ?int   $modifiedAt = null
    ) {
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

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed): self
    {
        $this->confirmed = $confirmed;
        return $this;
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
        return password_verify($password, $this->password);
    }
}
