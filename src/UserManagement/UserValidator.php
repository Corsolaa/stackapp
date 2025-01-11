<?php

namespace StackSite\UserManagement;

class UserValidator
{
    public const string ERROR_USERNAME = 'USERNAME_ERROR';
    public const string ERROR_EMAIL = 'EMAIL_ERROR';
    public const string ERROR_PASSWORD = 'PASSWORD_ERROR';
    public const string DUPLICATE_USERNAME = 'USERNAME_DUPLICATE';
    public const string DUPLICATE_EMAIL = 'EMAIL_DUPLICATE';
    private array $errors = [];

    public function __construct(
        private readonly UserPersistence $userPersistence
    )
    {
    }

    public function userIsKnown(User $user): bool
    {
        if ($this->userPersistence->fetchByEmail($user->getEmail()) !== null) {
            $this->errors[] = self::DUPLICATE_EMAIL;
        }

        if ($this->userPersistence->fetchByUsername($user->getUsername()) !== null) {
            $this->errors[] = self::DUPLICATE_USERNAME;
        }

        return !empty($this->errors);
    }

    public function hasValidParameters(User $user): bool
    {
        if (
            $user->getEmail() === '' ||
            filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL) === false
        ) {
            $this->errors[] = self::ERROR_EMAIL;
        }

        if (
            $user->getUsername() === '' ||
            strlen($user->getUsername()) < 3 ||
            strlen($user->getUsername()) > 30
        ) {
            $this->errors[] = self::ERROR_USERNAME;
        }

        if (
            $user->getPassword() === '' ||
            strlen($user->getPassword()) < 10 ||
            strlen($user->getPassword()) > 64
        ) {
            $this->errors[] = self::ERROR_PASSWORD;
        }

        return empty($this->errors);
    }

    public function hasValidEmail(string $email): bool
    {
        if (
            $email === '' ||
            filter_var($email, FILTER_VALIDATE_EMAIL) === false
        ) {
            $this->errors[] = self::ERROR_EMAIL;
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}