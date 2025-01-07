<?php

namespace StackSite\UserManagement;

use StackSite\Core\RequestBodyHandler;
use StackSite\UserManagement\Token\Mailing\TokenMailingServiceInterface;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\Token\UserTokenService;

class UserControllerFactory
{
    public static function create(TokenMailingServiceInterface $tokenMailingService): UserController
    {
        $requestBodyHandler = new RequestBodyHandler();
        $userPersistence = new UserPersistence();
        $tokenFactory = new TokenFactory();
        $tokenPersistence = new TokenPersistence();

        $userValidator = new UserValidator($userPersistence);
        $userTokenService = new UserTokenService($tokenPersistence, $tokenMailingService);
        $userRegistrationService = new UserRegistrationService(
            $userValidator,
            $userPersistence,
            $tokenFactory,
            $userTokenService
        );

        return new UserController($requestBodyHandler, $userRegistrationService);
    }
}