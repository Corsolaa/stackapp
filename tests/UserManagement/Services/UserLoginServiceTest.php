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
use StackSite\UserManagement\Token\UserTokenService;
use StackSite\UserManagement\User;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

class UserLoginServiceTest extends TestCase
{
    private UserValidator&MockObject $userValidator;
    private UserPersistence&MockObject $userPersistence;
    private TokenFactory&MockObject $tokenFactory;
    private UserTokenService&MockObject $userTokenService;
    private User&MockObject $user;
    private Token&MockObject $token;
    private UserLoginService $userLoginService;

    public function setUp(): void
    {
        $this->user             = $this->createMock(User::class);
        $this->token            = $this->createMock(Token::class);
        $this->userValidator    = $this->createMock(UserValidator::class);
        $this->userPersistence  = $this->createMock(UserPersistence::class);
        $this->tokenFactory     = $this->createMock(TokenFactory::class);
        $this->userTokenService = $this->createMock(UserTokenService::class);

        $this->user->method('getId')
            ->willReturn(1234);

        $this->userLoginService = new UserLoginService(
            $this->userValidator,
            $this->userPersistence,
            $this->tokenFactory,
            $this->userTokenService
        );
    }

    public function testLoginUserSuccessfully(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn($this->user);

        $this->user->method('verifyPassword')
            ->willReturn(true);

        $this->tokenFactory->method('generateLoginUser')
            ->willReturn($this->token);

        $this->token->method('getToken')
            ->willReturn('stack_token');

        $this->userTokenService->method('processUserLoginToken')
            ->willReturn(true);

        $response = $this->userLoginService->loginUser('test@stacksats.ai', 'password');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertTrue($response->getSuccess());
        $this->assertEquals('Success', $response->getMessage());
        $this->assertEquals(['token' => 'stack_token'], $response->getData());
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

    public function testLoginUserFailGenerateLogin(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn($this->user);

        $this->user->method('verifyPassword')
            ->willReturn(true);

        $this->tokenFactory->method('generateLoginUser')
            ->willReturn(null);

        $response = $this->userLoginService->loginUser('test@stacksats.ai', 'password');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid', $response->getMessage());
        $this->assertEquals(['code' => ErrorCodes::LOGIN_GENERATE_TOKEN], $response->getErrors());
    }

    public function testLoginUserFailProcessToken(): void
    {
        $this->userValidator->method('hasValidEmail')
            ->willReturn(true);

        $this->userPersistence->method('fetchByEmail')
            ->willReturn($this->user);

        $this->user->method('verifyPassword')
            ->willReturn(true);

        $this->tokenFactory->method('generateLoginUser')
            ->willReturn($this->token);

        $this->token->method('getToken')
            ->willReturn('stack_token');

        $this->userTokenService->method('processUserLoginToken')
            ->willReturn(false);

        $response = $this->userLoginService->loginUser('test@stacksats.ai', 'password');

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid', $response->getMessage());
        $this->assertEquals(['code' => ErrorCodes::LOGIN_PROCES_TOKEN], $response->getErrors());
    }
}
