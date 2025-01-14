<?php

declare(strict_types=1);

namespace StackSite\Router\Routes;

use StackSite\Core\Mailing\EmailHandler;
use StackSite\Router\Route;
use StackSite\UserManagement\Factories\UserControllerFactory;
use StackSite\UserManagement\Token\Mailing\ConfirmVerifyTokenMailingService;
use StackSite\UserManagement\Token\Mailing\PasswordResetTokenMailingService;
use StackSite\UserManagement\Token\Mailing\TokenMailingServiceInterface;
use StackSite\UserManagement\Token\Mailing\VerifyTokenMailingService;

class UserRoute extends Route
{

    public function register(): void
    {
        $this->router->add('/user', $this);
    }

    public function handle(): void
    {
        $userController = UserControllerFactory::create($this->createTokenMailingService());

        if (isset($_GET['register'])) {
            echo $userController->registerUser()->toJson();
            return;
        }

        if (isset($_GET['verify'])) {
            echo $userController->verifyUser()->toJson();
            return;
        }

        if (isset($_GET['login'])) {
            echo $userController->loginUser()->toJson();
            return;
        }

        if (isset($_GET['password_reset'])) {
            echo $userController->passwordResetUser()->toJson();
            return;
        }

        echo $this->showPreviewPage();
    }

    private function showPreviewPage(): string
    {
        $options = ['register'];
        $return  = '<h2>Subscribe page</h2> <br> options: <ul>';
        foreach ($options as $option) {
            $return .= "<li><a href='/subscribe?$option'>https://app.stacksats.ai/subscribe?$option</a></li>";
        }
        return $return . "</ul>";
    }

    private function createTokenMailingService(): TokenMailingServiceInterface
    {
        $emailHandler = new EmailHandler($_ENV['NOREPLY_MAILADRES'], $_ENV['NOREPLY_FROM_NAME']);

        if (isset($_GET['password_reset'])) {
            return new PasswordResetTokenMailingService($emailHandler);
        }

        if (isset($_GET['verify'])) {
            return new ConfirmVerifyTokenMailingService($emailHandler);
        }

        return new VerifyTokenMailingService($emailHandler);
    }
}