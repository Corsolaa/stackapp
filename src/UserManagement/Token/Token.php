<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

class Token
{
    const string VERIFY = 'verify_user';
    const string PASSWORD = 'password_reset';
    const string LOGIN = 'login_user';

    public function __construct(
        private ?int            $id = null,
        private readonly ?int   $user_id = null,
        private readonly string $token = '',
        private readonly string $type = '',
        private readonly int    $expires_at = 0,
        private readonly int    $created_at = 0
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

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getExpiresAt(): int
    {
        return $this->expires_at;
    }

    public function getCreatedAt(): int
    {
        return $this->created_at;
    }
}