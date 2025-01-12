<?php

declare(strict_types=1);

namespace StackSite\Tests\UserManagement\Services;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StackSite\Core\Api\ApiResponse;
use StackSite\UserManagement\Services\UserResetPasswordService;
use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\UserTokenService;
use StackSite\UserManagement\User;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

class UserResetPasswordServiceTest extends TestCase
{
    private UserValidator&MockObject $userValidator;
    private UserPersistence&MockObject $userPersistence;
    private TokenFactory&MockObject $tokenFactory;
    private UserTokenService&MockObject $userTokenService;
    private UserResetPasswordService $userResetPasswordService;

    public function setUp(): void
    {
        $this->userValidator = $this->createMock(UserValidator::class);
        $this->userPersistence = $this->createMock(UserPersistence::class);
        $this->tokenFactory = $this->createMock(TokenFactory::class);
        $this->userTokenService = $this->createMock(UserTokenService::class);

        $this->userResetPasswordService = new UserResetPasswordService(
          $this->userValidator,
          $this->userPersistence,
          $this->tokenFactory,
          $this->userTokenService
        );

        parent::setUp();
    }

    public function testResetPasswordSuccessfully(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn(new User(1));

        $this->tokenFactory->method('generatePasswordReset')
            ->willReturn(new Token);

        $this->userTokenService->method('processUserPasswordResetToken')
            ->willReturn(true);

        $response = $this->userResetPasswordService->resetUserPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertTrue($response->getSuccess());
        $this->assertEquals('Token reset successfully', $response->getMessage());
    }

    public function testResetPasswordFailValidEmail(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(false);

        $response = $this->userResetPasswordService->resetUserPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid email', $response->getMessage());
    }

    public function testResetPasswordFailFetchUserByEmail(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn(null);

        $response = $this->userResetPasswordService->resetUserPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Unknown user', $response->getMessage());
    }

    public function testResetPasswordFailGenerateToken(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn(new User(1));

        $this->tokenFactory->method('generatePasswordReset')
            ->willReturn(null);

        $response = $this->userResetPasswordService->resetUserPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Token error', $response->getMessage());
    }

    public function testResetPasswordFailProcessToken(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn(new User(1));

        $this->tokenFactory->method('generatePasswordReset')
            ->willReturn(new Token);

        $this->userTokenService->method('processUserPasswordResetToken')
            ->willReturn(false);

        $response = $this->userResetPasswordService->resetUserPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Token process error', $response->getMessage());
    }
}
