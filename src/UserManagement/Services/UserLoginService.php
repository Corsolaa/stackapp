<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Services;

use StackSite\Core\Api\ApiResponse;
use StackSite\Core\Exceptions\ErrorCodes;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\UserTokenService;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

readonly class UserLoginService
{
    public function __construct(
        private UserValidator    $userValidator,
        private UserPersistence  $userPersistence,
        private TokenFactory     $tokenFactory,
        private UserTokenService $userTokenService,
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

        $loginToken = $this->tokenFactory->generateLoginUser($user->getId());
        if ($loginToken === null) {
            return new ApiResponse(false, 'Invalid', ['code' => ErrorCodes::LOGIN_GENERATE_TOKEN]);
        }

        if ($this->userTokenService->processUserLoginToken($loginToken)) {
            return new ApiResponse(true, 'Success', [], ['token' => $loginToken->getToken()]);
        } else {
            return new ApiResponse(false, 'Invalid', ['code' => ErrorCodes::LOGIN_PROCES_TOKEN]);
        }
    }
}