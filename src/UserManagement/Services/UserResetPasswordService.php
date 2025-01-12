<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Services;

use StackSite\Core\Api\ApiResponse;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\UserTokenService;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

readonly class UserResetPasswordService
{
    public function __construct(
        private UserValidator    $userValidator,
        private UserPersistence  $userPersistence,
        private TokenFactory     $tokenFactory,
        private UserTokenService $userTokenService,
    ) {
    }

    public function resetUserPassword(string $email): ApiResponse
    {
        if ($this->userValidator->hasValidEmail($email) === false) {
            return new ApiResponse(false, 'Invalid email');
        }

        $user = $this->userPersistence->fetchByEmail($email);
        if ($user === null) {
            return new ApiResponse(false, 'Unknown user');
        }

        $passwordToken = $this->tokenFactory->generatePasswordReset($user->getId());
        if ($passwordToken === null) {
            return new ApiResponse(false, 'Token error');
        }

        if ($this->userTokenService->processUserPasswordResetToken($user, $passwordToken)) {
            return new ApiResponse(true, 'Token reset successfully');
        } else {
            return new ApiResponse(false, 'Token process error');
        }
    }
}