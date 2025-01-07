<?php

namespace StackSite\Tests\UserManagement;

use PHPUnit\Framework\TestCase;
use StackSite\Core\Api\ApiResponse;
use StackSite\UserManagement\Token\Token;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\UserTokenService;
use StackSite\UserManagement\User;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserRegistrationService;
use StackSite\UserManagement\UserValidator;

class UserRegistrationServiceTest extends TestCase
{
    private UserValidator $userValidator;
    private UserPersistence $userPersistence;
    private TokenFactory $tokenFactory;
    private UserTokenService $userTokenService;
    private UserRegistrationService $userRegistrationService;

    public function setUp(): void
    {
        $this->userValidator = $this->createMock(UserValidator::class);
        $this->userPersistence = $this->createMock(UserPersistence::class);
        $this->tokenFactory = $this->createMock(TokenFactory::class);
        $this->userTokenService = $this->createMock(UserTokenService::class);

        $this->userRegistrationService = new UserRegistrationService(
            $this->userValidator,
            $this->userPersistence,
            $this->tokenFactory,
            $this->userTokenService
        );

        parent::setUp();
    }

    public function testRegisterUserSuccessful(): void
    {
        $user = new User();

        $this->userValidator
            ->method('hasValidParameters')
            ->willReturn(true);

        $this->userValidator
            ->method('userIsKnown')
            ->willReturn(false);

        $this->tokenFactory
            ->method('generateVerify')
            ->willReturn($this->createMock(Token::class));

        $this->userTokenService
            ->method('processUserToken')
            ->willReturn(true);

        $response = $this->userRegistrationService->registerUser($user);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertTrue($response->getSuccess());
        $this->assertEquals('User registered successfully', $response->getMessage());
    }

    public function testRegisterUserFailedParameters(): void
    {
        $user = new User();

        $this->userValidator
            ->method('hasValidParameters')
            ->willReturn(false);

        $response = $this->userRegistrationService->registerUser($user);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Invalid parameters', $response->getMessage());
    }

    public function testRegisterUserFailedUserIsKnown(): void
    {
        $user = new User();

        $this->userValidator
            ->method('hasValidParameters')
            ->willReturn(true);

        $this->userValidator
            ->method('userIsKnown')
            ->willReturn(true);

        $response = $this->userRegistrationService->registerUser($user);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('User already exists', $response->getMessage());
    }

    public function testRegisterUserFailedToken(): void
    {
        $user = new User();

        $this->userValidator
            ->method('hasValidParameters')
            ->willReturn(true);

        $this->userValidator
            ->method('userIsKnown')
            ->willReturn(false);

        $this->tokenFactory
            ->method('generateVerify')
            ->willReturn(null);

        $response = $this->userRegistrationService->registerUser($user);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Token generation failed', $response->getMessage());
    }

    public function testRegisterUserFailedMailSend(): void
    {
        $user = new User();

        $this->userValidator
            ->method('hasValidParameters')
            ->willReturn(true);

        $this->userValidator
            ->method('userIsKnown')
            ->willReturn(false);

        $this->tokenFactory
            ->method('generateVerify')
            ->willReturn($this->createMock(Token::class));

        $this->userTokenService
            ->method('processUserToken')
            ->willReturn(false);

        $response = $this->userRegistrationService->registerUser($user);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Mail could not be sent', $response->getMessage());
    }
}
