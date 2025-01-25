<?php

declare(strict_types=1);

namespace StackSite\Tests\UserManagement\Token;

use PHPUnit\Framework\TestCase;
use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenValidator;

class TokenValidatorTest extends TestCase
{
    private TokenValidator $tokenValidator;

    public function setUp(): void
    {
        $this->tokenValidator = new TokenValidator();
        parent::setUp();
    }

    public function testIsExpired()
    {
        $token  = new Token(expires_at: time() - 1);
        $result = $this->tokenValidator->isExpired($token);

        $this->assertTrue($result);
    }

    public function testIsNotExpired()
    {
        $token  = new Token(expires_at: time() + 1);
        $result = $this->tokenValidator->isExpired($token);

        $this->assertFalse($result);
    }
}
