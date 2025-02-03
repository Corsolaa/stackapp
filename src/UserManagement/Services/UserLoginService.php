<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Services;

use StackSite\Core\Api\ApiResponse;
use StackSite\Core\Exceptions\ErrorCodes;
use StackSite\Core\Http\SessionHub;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

readonly class UserLoginService
{
    public function __construct(
        private UserValidator   $userValidator,
        private UserPersistence $userPersistence,
        private SessionHub      $sessionHub,
    ) {
    }

    public function loginUser(string $email, string $password): ApiResponse
    {
        if ($this->userValidator->hasValidEmail($email) === false) {
            return new ApiResponse(false, 'Invalid', ['code' => ErrorCodes::LOGIN_INVALID_EMAIL]);
        }

        $user = $this->userPersistence->fetchByEmail($email);
        if ($user === null) {
            return new ApiResponse(false, 'Invalid', ['code' => ErrorCodes::LOGIN_UNKNOWN_USER]);
        }

        if ($user->verifyPassword($password) === false) {
            return new ApiResponse(false, 'Invalid', ['code' => ErrorCodes::LOGIN_INVALID_PASSWORD]);
        }

        $this->sessionHub->loginUser($user->getId());

        return new ApiResponse(true, 'Success');
    }
}