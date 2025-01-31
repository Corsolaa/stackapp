<?php

namespace StackSite\Router\Routes;

use StackSite\Core\Template;
use StackSite\Router\Route;
use StackSite\UserManagement\Factories\UserControllerFactory;

class VerifyRoute extends Route {

    public function register(): void
    {
        $this->router->add('/user/verify', $this);
    }

    public function handle(): void
    {
        Template::getHeader("StackSats ~ Verify User", []);

        // get the token en verify it and set the result
        $userController = UserControllerFactory::create();
        $_SESSION['verify_good'] = $userController->verifyUser()->getSuccess();

        require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/verify_page.php';

        Template::getFooter();
    }
}