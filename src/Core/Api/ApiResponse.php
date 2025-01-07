<?php

namespace StackSite\Core\Api;

class ApiResponse
{
    private bool $success;
    private string $message;
    private array $errors;
    private array $data;

    public function __construct(bool $success, string $message = '', array $errors = [], array $data = [])
    {
        $this->success = $success;
        $this->message = $message;
        $this->errors = $errors;
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'errors' => $this->errors,
            'data' => $this->data,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
