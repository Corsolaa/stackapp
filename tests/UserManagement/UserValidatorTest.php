<?php

declare(strict_types=1);

namespace StackSite\Tests\UserManagement;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StackSite\UserManagement\User;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

class UserValidatorTest extends TestCase
{
    private UserValidator $userValidator;
    private UserPersistence&MockObject $userPersistence;

    public function setUp(): void
    {
        $this->userPersistence = $this->createMock(UserPersistence::class);
        $this->userValidator = new UserValidator($this->userPersistence);

        parent::setUp();
    }

    public function testUserIsKnown(): void
    {
        $this->userPersistence
            ->method('fetchByEmail')
            ->willReturn(null);
        $this->userPersistence
            ->method('fetchByUsername')
            ->willReturn(null);

        $result = $this->userValidator->userIsKnown(new User);

        $this->assertFalse($result);
        $this->assertCount(0, $this->userValidator->getErrors());
    }

    public function testUserIsKnownOnUsername(): void
    {
        $this->userPersistence
            ->method('fetchByEmail')
            ->willReturn(null);
        $this->userPersistence
            ->method('fetchByUsername')
            ->willReturn(new User);

        $result = $this->userValidator->userIsKnown(new User);

        $this->assertTrue($result);
        $this->assertCount(1, $this->userValidator->getErrors());
        $this->assertContains(UserValidator::DUPLICATE_USERNAME, $this->userValidator->getErrors());
    }

    public function testUserIsKnownOnEmail(): void
    {
        $this->userPersistence
            ->method('fetchByEmail')
            ->willReturn(new User);
        $this->userPersistence
            ->method('fetchByUsername')
            ->willReturn(null);

        $result = $this->userValidator->userIsKnown(new User);

        $this->assertTrue($result);
        $this->assertCount(1, $this->userValidator->getErrors());
        $this->assertContains(UserValidator::DUPLICATE_EMAIL, $this->userValidator->getErrors());
    }

    public function testUserIsKnownOnBoth(): void
    {
        $this->userPersistence
            ->method('fetchByEmail')
            ->willReturn(new User);
        $this->userPersistence
            ->method('fetchByUsername')
            ->willReturn(new User);

        $result = $this->userValidator->userIsKnown(new User);

        $this->assertTrue($result);
        $this->assertCount(2, $this->userValidator->getErrors());
        $this->assertContains(UserValidator::DUPLICATE_EMAIL, $this->userValidator->getErrors());
        $this->assertContains(UserValidator::DUPLICATE_USERNAME, $this->userValidator->getErrors());
    }


    public function testHasValidParametersWithValidUser(): void
    {
        $user = new User(
            username: 'ValidUsername',
            email: 'user@example.com',
            password: 'ValidPassword123!'
        );

        $this->assertTrue($this->userValidator->hasValidParameters($user));
        $this->assertEmpty($this->userValidator->getErrors());
    }

    public function testHasValidParametersWithInvalidEmail(): void
    {
        $user = new User(
            username: 'ValidUsername',
            email: 'invalid-email',
            password: 'ValidPassword123!'
        );

        $this->assertFalse($this->userValidator->hasValidParameters($user));
        $this->assertContains(UserValidator::ERROR_EMAIL, $this->userValidator->getErrors());
    }

    public function testHasValidParametersWithShortUsername(): void
    {
        $user = new User(
            username: 'Us',
            email: 'user@example.com',
            password: 'ValidPassword123!'
        );

        $this->assertFalse($this->userValidator->hasValidParameters($user));
        $this->assertContains(UserValidator::ERROR_USERNAME, $this->userValidator->getErrors());
    }

    public function testHasValidParametersWithEmptyPassword(): void
    {
        $user = new User(
            username: 'ValidUsername',
            email: 'user@example.com',
            password: ''
        );

        $this->assertFalse($this->userValidator->hasValidParameters($user));
        $this->assertContains(UserValidator::ERROR_PASSWORD, $this->userValidator->getErrors());
    }

    public function testHasValidParametersWithMultipleErrors(): void
    {
        $user = new User(
            username: '',
            email:  'invalid-email',
            password: ''
        );

        $this->assertFalse($this->userValidator->hasValidParameters($user));
        $this->assertCount(3, $this->userValidator->getErrors());
        $this->assertContains(UserValidator::ERROR_EMAIL, $this->userValidator->getErrors());
        $this->assertContains(UserValidator::ERROR_USERNAME, $this->userValidator->getErrors());
        $this->assertContains(UserValidator::ERROR_PASSWORD, $this->userValidator->getErrors());
    }
}
