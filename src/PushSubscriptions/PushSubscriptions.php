<?php

namespace StackSite\PushSubscriptions;

class PushSubscriptions
{
    private ?int $id;
    private int $user_id;
    private string $name;
    private string $endpoint;
    private string $p256dh;
    private string $auth;
    private ?int $created_at;

    public function __construct(
        ?int $id, int $user_id, string $name, string $endpoint, string $p256dh, string $auth, ?int $created_at = null)
    {
        $this->id         = $id;
        $this->user_id    = $user_id;
        $this->name       = $name;
        $this->endpoint   = $endpoint;
        $this->p256dh     = $p256dh;
        $this->auth       = $auth;
        $this->created_at = $created_at;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['user_id'] ?? null,
            $data['name'] ?? '',
            $data['endpoint'] ?? '',
            $data['p256dh'] ?? '',
            $data['auth'] ?? '',
            $data['created_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'device_name' => $this->name,
            'endpoint' => $this->endpoint,
            'p256dh' => $this->p256dh,
            'auth' => $this->auth,
            'created_at' => $this->created_at,
        ];
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setEndpoint(?string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setP256dh(?string $p256dh): self
    {
        $this->p256dh = $p256dh;
        return $this;
    }

    public function getP256dh(): ?string
    {
        return $this->p256dh;
    }

    public function setAuth(?string $auth): self
    {
        $this->auth = $auth;
        return $this;
    }

    public function getAuth(): ?string
    {
        return $this->auth;
    }

    public function setCreatedAt(?int $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

}
