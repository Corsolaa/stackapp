<?php

declare(strict_types=1);

namespace StackSite\Tests\UserManagement\Services;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StackSite\Core\Api\ApiResponse;
use StackSite\Core\Exceptions\ErrorCodes;
use StackSite\UserManagement\Services\UserLoginService;
use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\User;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

class UserLoginServiceTest extends TestCase
{
    private UserValidator&MockObject $userValidator;
    private UserPersistence&MockObject $userPersistence;
    private User&MockObject $user;
    private TokenFactory&MockObject $tokenFactory;
    private UserLoginService $userLoginService;

    public function setUp(): void
    {
        $this->user             = $this->createMock(User::class);
        $this->userValidator    = $this->createMock(UserValidator::class);
        $this->userPersistence  = $this->createMock(UserPersistence::class);
        $this->tokenFactory     = $this->createMock(TokenFactory::class);

        $this->user->method('getId')
            ->willReturn(1234);

        $this->userLoginService = new UserLoginService(
            $this->userValidator,
            $this->userPersistence,
            $this->createMock(TokenPersistence::class),
            $this->tokenFactory,
        );
    }

    public function testLoginUserSuccessfully(): void
    {
        $this->userValidator->expects($this->once())->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->expects($this->once())->method('fetchByEmail')
            ->willReturn($this->user);

        $this->user->expects($this->once())->method('verifyPassword')
            ->willReturn(true);

        $this->tokenFactory->expects($this->once())->method('generateLoginUser')
            ->willReturn(new Token);

        $response = $this->userLoginService->loginUser('test@stacksats.ai', 'password');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertTrue($response->getSuccess());
        $this->assertEquals('Success', $response->getMessage());
    }

    public function testLoginUserFailValidEmail(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(false);

        $response = $this->userLoginService->loginUser('test@stacksats.ai', 'password');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid', $response->getMessage());
        $this->assertEquals(['code' => ErrorCodes::LOGIN_INVALID_EMAIL], $response->getErrors());
    }

    public function testLoginUserFailFetchByEmail(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn(null);

        $response = $this->userLoginService->loginUser('test@stacksats.ai', 'password');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid', $response->getMessage());
        $this->assertEquals(['code' => ErrorCodes::LOGIN_UNKNOWN_USER], $response->getErrors());
    }

    public function testLoginUserFailVerifyPassword(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn($this->user);

        $this->user->method('verifyPassword')
            ->willReturn(false);

        $response = $this->userLoginService->loginUser('test@stacksats.ai', 'password');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid', $response->getMessage());
        $this->assertEquals(['code' => ErrorCodes::LOGIN_INVALID_PASSWORD], $response->getErrors());
    }

    public function testLoginUserFailGenerateToken(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn($this->user);

        $this->user->method('verifyPassword')
            ->willReturn(true);

        $this->tokenFactory->expects($this->once())->method('generateLoginUser')
            ->willReturn(null);

        $response = $this->userLoginService->loginUser('test@stacksats.ai', 'password');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid', $response->getMessage());
        $this->assertEquals(['code' => ErrorCodes::LOGIN_GENERATE_TOKEN], $response->getErrors());
    }
}
