<?php

declare(strict_types=1);

namespace StackSite\UserManagement;

use StackSite\Core\Api\ApiResponse;
use StackSite\Core\RequestBodyHandler;
use StackSite\UserManagement\Factories\UserFactory;
use StackSite\UserManagement\Services\UserLoginService;
use StackSite\UserManagement\Services\UserRegistrationService;
use StackSite\UserManagement\Services\UserResetPasswordService;
use StackSite\UserManagement\Services\UserVerifyService;
use StackSite\UserManagement\Token\Token;

readonly class UserController
{
    public function __construct(
        private RequestBodyHandler       $requestBody,
        private UserRegistrationService  $userRegistrationService,
        private UserVerifyService        $userVerifyService,
        private UserLoginService         $userLoginService,
        private UserResetPasswordService $userResetPasswordService,
    ) {
    }

    public function registerUser(): ApiResponse
    {
        $user = UserFactory::fromArray($this->requestBody->getAll(['username', 'email', 'password']));
        return $this->userRegistrationService->registerUser($user);
    }

    public function verifyUser(): ApiResponse
    {
        $token = new Token(
            token: $_GET['verify'] ?? '',
            type:  Token::VERIFY
        );

        if ($this->userVerifyService->verifyUser($token)) {
            return new ApiResponse(true, 'Account is verified');
        } else {
            return new ApiResponse(false, 'Invalid token presented');
        }
    }

    public function loginUser(): ApiResponse
    {
        $email    = $this->requestBody->get('email');
        $password = $this->requestBody->get('password');

        return $this->userLoginService->loginUser($email, $password);
    }

    public function passwordResetUser(): ApiResponse
    {
        $email = $this->requestBody->get('email');

        $this->userResetPasswordService->resetUserPassword((string)$email);

        return new ApiResponse(true, 'Password reset successfully');
    }
}