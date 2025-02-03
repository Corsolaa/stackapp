<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Factories;

use StackSite\Core\Http\SessionHub;
use StackSite\Core\Mailing\Template\EmailTemplateFactory;
use StackSite\Core\Mailing\Template\EmailTemplateService;
use StackSite\Core\Mailing\Template\TemplatePersistence;
use StackSite\Core\Mailing\Template\TemplateRenderer;
use StackSite\Core\RequestBodyHandler;
use StackSite\UserManagement\Services\UserLoginService;
use StackSite\UserManagement\Services\UserRegistrationService;
use StackSite\UserManagement\Services\UserResetPasswordService;
use StackSite\UserManagement\Services\UserVerifyService;
use StackSite\UserManagement\Token\TokenFactory;
use StackSite\UserManagement\Token\TokenPersistence;
use StackSite\UserManagement\Token\TokenValidator;
use StackSite\UserManagement\Token\UserTokenService;
use StackSite\UserManagement\UserController;
use StackSite\UserManagement\UserPersistence;
use StackSite\UserManagement\UserValidator;

class UserControllerFactory
{
    public static function create(): UserController
    {
        $requestBodyHandler   = new RequestBodyHandler();
        $userPersistence      = new UserPersistence();
        $tokenFactory         = new TokenFactory();
        $tokenValidator       = new TokenValidator();
        $emailTemplateService = new EmailTemplateService(
            new TemplateRenderer(),
            new TemplatePersistence(new EmailTemplateFactory()),
        );

        $tokenPersistence = new TokenPersistence($tokenFactory);
        $userValidator    = new UserValidator($userPersistence);
        $userTokenService = new UserTokenService(
            $tokenPersistence,
            $emailTemplateService,
            $tokenValidator,
            $userPersistence,
        );

        $userRegistrationService = new UserRegistrationService(
            $userValidator,
            $userPersistence,
            $tokenFactory,
            $userTokenService,
        );

        $userLoginService = new UserLoginService(
            $userValidator,
            $userPersistence,
            $tokenPersistence,
            $tokenFactory,
        );

        $userVerifyService = new UserVerifyService(
            $tokenPersistence,
            $userPersistence,
            $userTokenService,
        );

        $userResetPasswordService = new UserResetPasswordService(
            $userValidator,
            $userPersistence,
            $tokenFactory,
            $userTokenService,
            $tokenPersistence,
            $tokenValidator,
        );

        return new UserController(
            $requestBodyHandler,
            $userRegistrationService,
            $userVerifyService,
            $userLoginService,
            $userResetPasswordService,
            $userTokenService,
        );
    }
}