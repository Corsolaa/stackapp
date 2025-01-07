<?php

namespace StackSite\Router\Routes;

use StackSite\Core\Mailing\EmailHandler;
use StackSite\Router\Route;
use StackSite\UserManagement\Token\Mailing\VerifyTokenMailingService;
use StackSite\UserManagement\UserControllerFactory;

class UserRoute extends Route
{

    public function register(): void
    {
        $this->router->add('/user', $this);
    }

    public function handle(): void
    {
        $userController      = UserControllerFactory::create(
            new VerifyTokenMailingService(
                new EmailHandler($_ENV['NOREPLY_MAILADRES'], $_ENV['NOREPLY_FROM_NAME'])
            )
        );

        if (isset($_GET['register'])) {
            echo $userController->registerUser()
                ->toJson();
            return;
        }

        echo $this->showPreviewPage();
    }

    private function showPreviewPage(): string
    {
        $options = ['register'];
        $return = '<h2>Subscribe page</h2> <br> options: <ul>';
        foreach ($options as $option) {
            $return .= "<li><a href='/subscribe?$option'>https://app.stacksats.ai/subscribe?$option</a></li>";
        }
        return $return . "</ul>";
    }
}