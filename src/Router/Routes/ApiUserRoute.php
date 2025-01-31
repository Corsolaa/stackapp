<?php

declare(strict_types=1);

namespace StackSite\Router\Routes;

use StackSite\Router\Route;
use StackSite\UserManagement\Factories\UserControllerFactory;

class ApiUserRoute extends Route
{

    public function register(): void
    {
        $this->router->add('/api/user', $this);
    }

    public function handle(): void
    {
        $userController = UserControllerFactory::create();

        if (isset($_GET['register'])) {
            echo $userController->registerUser()->toJson();
            return;
        }

        if (isset($_GET['login'])) {
            echo $userController->loginUser()->toJson();
            return;
        }

        if (isset($_GET['password_reset'])) {
            echo $userController->sendPasswordReset()->toJson();
            return;
        }

        if (isset($_GET['process_password_reset'])) {
            echo $userController->verifyPasswordReset()->toJson();
            return;
        }

        $this->showPreviewPage();
    }

    private function showPreviewPage(): void
    {
        $options = [
            'register',
            'login',
            'password_reset',
            'process_password_reset'
        ];
        $return  = '<h2>Subscribe page</h2> <br> options: <ul>';
        foreach ($options as $option) {
            $return .= "<li><a href='/api/user?$option'>https://app.stacksats.ai/api/user?$option</a></li>";
        }
        echo $return . "</ul>";
    }
}