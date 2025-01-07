<?php

namespace StackSite\UserManagement;

use StackSite\Core\Api\ApiResponse;
use StackSite\Core\RequestBodyHandler;

readonly class UserController
{
    public function __construct(
        private RequestBodyHandler $requestBody,
        private UserRegistrationService  $userRegistrationService,
    )
    {
    }

    public function registerUser(): ApiResponse
    {
        $user = UserFactory::fromArray($this->requestBody->getAll(['username', 'email', 'password']));
        return $this->userRegistrationService->registerUser($user);
    }
}