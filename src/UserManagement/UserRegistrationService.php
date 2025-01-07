<?php

namespace StackSite\UserManagement;

use StackSite\Core\Api\ApiResponse;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\UserTokenService;

readonly class UserRegistrationService
{
    public function __construct(
        private UserValidator        $userValidator,
        private UserPersistence      $userPersistence,
        private TokenFactory         $tokenFactory,
        private UserTokenService     $userTokenService,
    ) {}

    public function registerUser(User $user): ApiResponse
    {
        if ($this->userValidator->hasValidParameters($user) === false) {
            return new ApiResponse(false, 'Invalid parameters', $this->userValidator->getErrors());
        }

        if ($this->userValidator->userIsKnown($user)) {
            return new ApiResponse(false, 'User already exists', $this->userValidator->getErrors()
            );
        }

        $user->sanitizeUsername();
        $user->hashPassword();

        $userId = $this->userPersistence->setUser($user)
            ->upload();

        $user->setId($userId);

        $token = $this->tokenFactory->generateVerify($user->getId());
        if ($token === null) {
            return new ApiResponse(false, 'Token generation failed');
        }

        if ($this->userTokenService->processUserToken($user, $token)) {
            return new ApiResponse(true, 'User registered successfully');
        } else {
            return new ApiResponse(false, 'Mail could not be sent');
        }
    }
}