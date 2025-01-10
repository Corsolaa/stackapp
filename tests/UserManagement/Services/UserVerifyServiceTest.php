<?php

declare(strict_types=1);

namespace StackSite\Tests\UserManagement\Services;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StackSite\UserManagement\Services\UserVerifyService;
use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\User;
use StackSite\UserManagement\UserPersistence;

class UserVerifyServiceTest extends TestCase
{
    private UserVerifyService $userVerifyService;
    private TokenPersistence&MockObject $tokenPersistence;
    private UserPersistence&MockObject $userPersistence;

    public function setUp(): void
    {
        $this->tokenPersistence = $this->createMock(TokenPersistence::class);
        $this->userPersistence  = $this->createMock(UserPersistence::class);

        $this->userVerifyService = new UserVerifyService(
            $this->tokenPersistence,
            $this->userPersistence
        );

        parent::setUp();
    }

    public function testVerifyUserSuccessfully(): void
    {
        $token = new Token(45, 12, 'token_string');
        $user  = new User();

        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn($token);

        $this->userPersistence->method('fetchByUserId')
            ->willReturn($user);

        $this->userPersistence->method('confirmUser')
            ->willReturn(true);

        $this->tokenPersistence->method('deleteById')
            ->willReturn(true);

        $result = $this->userVerifyService->verifyUser($token);

        self::assertTrue($result);
    }

    public function testVerifyUserFailGetToken(): void
    {
        $token = new Token(token: '');

        $result = $this->userVerifyService->verifyUser($token);

        self::assertFalse($result);
    }

    public function testVerifyUserFailFetchToken(): void
    {
        $token = null;

        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn($token);

        $result = $this->userVerifyService->verifyUser(new Token);

        self::assertFalse($result);
    }

    public function testVerifyUserFailFetchUser(): void
    {
        $token = new Token(45, 12, 'token_string');
        $user  = null;

        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn($token);

        $this->userPersistence->method('fetchByUserId')
            ->willReturn($user);

        $result = $this->userVerifyService->verifyUser(new Token);

        self::assertFalse($result);
    }

    public function testVerifyUserFailConfirmUser(): void
    {
        $token = new Token(45, 12, 'token_string');
        $user  = new User();

        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn($token);

        $this->userPersistence->method('fetchByUserId')
            ->willReturn($user);

        $this->userPersistence->method('confirmUser')
            ->willReturn(false);

        $result = $this->userVerifyService->verifyUser(new Token);

        self::assertFalse($result);
    }

    public function testVerifyUserFailDeleteToken(): void
    {
        $token = new Token(45, 12, 'token_string');
        $user  = new User();

        $this->tokenPersistence->method('fetchByTokenAndType')
            ->willReturn($token);

        $this->userPersistence->method('fetchByUserId')
            ->willReturn($user);

        $this->userPersistence->method('confirmUser')
            ->willReturn(true);

        $this->tokenPersistence->method('deleteById')
            ->willReturn(false);

        $result = $this->userVerifyService->verifyUser(new Token);

        self::assertFalse($result);
    }
}
