<?php

declare(strict_types=1);

namespace StackSite\Tests\UserManagement\Services;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StackSite\Core\Api\ApiResponse;
use StackSite\UserManagement\Services\UserResetPasswordService;
use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\Token\TokenValidator;
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
    private TokenPersistence&MockObject $tokenPersistence;
    private TokenValidator&MockObject $tokenValidator;
    private UserResetPasswordService $userResetPasswordService;

    public function setUp(): void
    {
        $this->userValidator    = $this->createMock(UserValidator::class);
        $this->userPersistence  = $this->createMock(UserPersistence::class);
        $this->tokenFactory     = $this->createMock(TokenFactory::class);
        $this->userTokenService = $this->createMock(UserTokenService::class);
        $this->tokenPersistence = $this->createMock(TokenPersistence::class);
        $this->tokenValidator = $this->createMock(TokenValidator::class);

        $this->userResetPasswordService = new UserResetPasswordService(
            $this->userValidator,
            $this->userPersistence,
            $this->tokenFactory,
            $this->userTokenService,
            $this->tokenPersistence,
            $this->tokenValidator
        );

        parent::setUp();
    }

    public function testSendResetPasswordSuccessfully(): void
    {
        $this->userValidator->expects($this->once())->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->expects($this->once())->method('fetchByEmail')
            ->willReturn(new User(1));

        $this->tokenFactory->expects($this->once())->method('generatePasswordReset')
            ->willReturn(new Token);

        $this->userTokenService->expects($this->once())->method('processUserPasswordResetToken')
            ->willReturn(true);

        $response = $this->userResetPasswordService->sendResetPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertTrue($response->getSuccess());
        $this->assertEquals('Token reset successfully', $response->getMessage());
    }

    public function testSendResetPasswordFailValidEmail(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(false);

        $response = $this->userResetPasswordService->sendResetPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid email', $response->getMessage());
    }

    public function testSendResetPasswordFailFetchUserByEmail(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn(null);

        $response = $this->userResetPasswordService->sendResetPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Unknown user', $response->getMessage());
    }

    public function testSendResetPasswordFailGenerateToken(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn(new User(1));

        $this->tokenFactory->method('generatePasswordReset')
            ->willReturn(null);

        $response = $this->userResetPasswordService->sendResetPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Token error', $response->getMessage());
    }

    public function testSendResetPasswordFailProcessToken(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn(new User(1));

        $this->tokenFactory->method('generatePasswordReset')
            ->willReturn(new Token);

        $this->userTokenService->method('processUserPasswordResetToken')
            ->willReturn(false);

        $response = $this->userResetPasswordService->sendResetPassword('test@stacksats.ai');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Token process error', $response->getMessage());
    }

    public function testProcessResetPasswordTokenSuccessfully(): void
    {
        $this->tokenPersistence->expects($this->once())->method('fetchByTokenAndType')
            ->willReturn(new Token(id: 1, user_id: 1));

        $this->tokenValidator->expects($this->once())->method('isExpired')
            ->willReturn(false);

        $this->userPersistence->expects($this->once())->method('fetchByUserId')
            ->willReturn(new User(1));

        $this->userValidator->expects($this->once())->method('hasValidPassword')
            ->willReturn(true);

        $this->userPersistence->expects($this->once())->method('updatePassword')
            ->willReturn(true);

        $response = $this->userResetPasswordService->processResetPasswordToken('', '');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertTrue($response->getSuccess());
        $this->assertEquals('Password reset successfully', $response->getMessage());
    }

    public function testProcessResetPasswordTokenFailTokenNull(): void
    {
        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn(null);

        $response = $this->userResetPasswordService->processResetPasswordToken('', '');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Unknown token or expired', $response->getMessage());
    }

    public function testProcessResetPasswordTokenFailTokenInPast(): void
    {
        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn(new Token);

        $this->tokenValidator->method('isExpired')
            ->willReturn(true);

        $response = $this->userResetPasswordService->processResetPasswordToken('', '');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Unknown token or expired', $response->getMessage());
    }

    public function testProcessResetPasswordTokenFailUserNull(): void
    {
        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn(new Token(user_id: 1));

        $this->tokenValidator->method('isExpired')
            ->willReturn(false);

        $this->userPersistence->method('fetchByUserId')
            ->willReturn(null);

        $response = $this->userResetPasswordService->processResetPasswordToken('', '');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Unknown user for token', $response->getMessage());
    }

    public function testProcessResetPasswordTokenFailInvalidPassword(): void
    {
        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn(new Token(user_id: 1));

        $this->userPersistence->method('fetchByUserId')
            ->willReturn(new User(1));

        $this->userValidator->method('hasValidPassword')
            ->willReturn(false);

        $response = $this->userResetPasswordService->processResetPasswordToken('', '');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Incompatible password', $response->getMessage());
    }

    public function testProcessResetPasswordTokenFailInvalidQuery(): void
    {
        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn(new Token(user_id: 1));

        $this->userPersistence->method('fetchByUserId')
            ->willReturn(new User(1));

        $this->userValidator->method('hasValidPassword')
            ->willReturn(true);

        $this->userPersistence->method('updatePassword')
            ->willReturn(false);

        $response = $this->userResetPasswordService->processResetPasswordToken('', '');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Failed to update password', $response->getMessage());
    }
}
