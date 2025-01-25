<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Services;

use StackSite\Core\Api\ApiResponse;
use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\TokenManager;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\Token\TokenValidator;
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
        private TokenPersistence $tokenPersistence,
        private TokenValidator   $tokenValidator,
        private TokenManager     $tokenManager
    ) {
    }

    public function sendResetPassword(string $email): ApiResponse
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

        if ($this->tokenManager->checkDuplicateAndExpireToken($passwordToken) === false) {
            return new ApiResponse(false, 'Token duplicate');
        }

        if ($this->userTokenService->processUserPasswordResetToken($user, $passwordToken)) {
            return new ApiResponse(true, 'Token reset successfully');
        } else {
            return new ApiResponse(false, 'Token process error');
        }
    }

    public function processResetPasswordToken(string $tokenToken, string $password): ApiResponse
    {
        $token = $this->tokenPersistence->fetchByTokenAndType(new Token(token: $tokenToken, type: Token::PASSWORD));

        if ($token === null || $this->tokenValidator->isExpired($token)) {
            return new ApiResponse(false, 'Unknown token or expired');
        }

        $user = $this->userPersistence->fetchByUserId($token->getUserId());

        if ($user === null) {
            return new ApiResponse(false, 'Unknown user for token');
        }

        if ($this->userValidator->hasValidPassword($password) === false) {
            return new ApiResponse(false, 'Incompatible password');
        }

        $user->setPassword($password)->hashPassword();

        $this->userPersistence->setUser($user)->updatePassword();

        $this->tokenPersistence->deleteById($token->getId());

        // TODO send mail that password is reset

        return new ApiResponse(true, 'Password reset successfully');
    }
}