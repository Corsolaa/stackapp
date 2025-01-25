<?php

declare(strict_types=1);

namespace StackSite\Tests\UserManagement\Token;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenManager;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\Token\TokenValidator;

class TokenManagerTest extends TestCase
{
    private TokenPersistence&MockObject $tokenPersistence;
    private TokenValidator&MockObject $tokenValidator;
    private TokenManager $tokenManager;

    public function setUp(): void
    {
        $this->tokenPersistence = $this->createMock(TokenPersistence::class);
        $this->tokenValidator   = $this->createMock(TokenValidator::class);
        $this->tokenManager     = new TokenManager($this->tokenPersistence, $this->tokenValidator);

        parent::setUp();
    }

    public function testCheckAndExpireTokenTrue()
    {
        $token  = new Token(id: 1, user_id: 1, type: TOKEN::PASSWORD);

        $this->tokenPersistence->method('fetchByUserIdAndType')
            ->willReturn($token);

        $this->tokenValidator->method('isExpired')
            ->willReturn(false);

        $this->tokenPersistence->expects($this->never())
            ->method('deleteById');

        $result = $this->tokenManager->checkDuplicateAndExpireToken($token);

        $this->assertTrue($result);
    }

    public function testCheckAndExpireTokenFetchedNull()
    {
        $token  = new Token(user_id: 1, type: TOKEN::PASSWORD);
        $this->tokenPersistence->method('fetchByUserIdAndType')
            ->willReturn(null);

        $result = $this->tokenManager->checkDuplicateAndExpireToken($token);

        $this->assertFalse($result);
    }

    public function testCheckAndExpireTokenIsExpired()
    {
        $token  = new Token(id: 1, user_id: 1, type: TOKEN::PASSWORD);

        $this->tokenPersistence->method('fetchByUserIdAndType')
            ->willReturn($token);

        $this->tokenValidator->method('isExpired')
            ->willReturn(true);

        $this->tokenPersistence->expects($this->once())
            ->method('deleteById');

        $result = $this->tokenManager->checkDuplicateAndExpireToken($token);

        $this->assertFalse($result);
    }

    public function testCheckAndExpireTokenAvailable()
    {
        $token  = new Token(user_id: 1, type: TOKEN::PASSWORD);
        $result = $this->tokenManager->checkDuplicateAndExpireToken($token);

        $this->assertTrue($result);
    }
}
