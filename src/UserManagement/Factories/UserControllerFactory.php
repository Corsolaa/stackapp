<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Factories;

use StackSite\Core\RequestBodyHandler;
use StackSite\UserManagement\Services\UserLoginService;
use StackSite\UserManagement\Services\UserRegistrationService;
use StackSite\UserManagement\Services\UserResetPasswordService;
use StackSite\UserManagement\Services\UserVerifyService;
use StackSite\UserManagement\Token\Mailing\TokenMailingServiceInterface;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\Token\UserTokenService;
use StackSite\UserManagement\UserController;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

class UserControllerFactory
{
    public static function create(TokenMailingServiceInterface $tokenMailingService): UserController
    {
        $requestBodyHandler = new RequestBodyHandler();
        $userPersistence = new UserPersistence();
        $tokenFactory = new TokenFactory();
        $tokenPersistence = new TokenPersistence($tokenFactory);
        $userValidator = new UserValidator($userPersistence);
        $userTokenService = new UserTokenService($tokenPersistence, $tokenMailingService);

        $userRegistrationService = new UserRegistrationService(
            $userValidator,
            $userPersistence,
            $tokenFactory,
            $userTokenService
        );
        $userLoginService = new UserLoginService(
            $userValidator,
            $userPersistence,
            $tokenFactory,
            $userTokenService
        );
        $userVerifyService = new UserVerifyService(
            $tokenPersistence,
            $userPersistence
        );
        $userResetPasswordService = new UserResetPasswordService(
            $userValidator,
            $userPersistence,
            $tokenFactory,
            $userTokenService
        );

        return new UserController(
            $requestBodyHandler,
            $userRegistrationService,
            $userVerifyService,
            $userLoginService,
            $userResetPasswordService
        );
    }
}