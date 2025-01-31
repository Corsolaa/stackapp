<?php

namespace StackSite\Router\Routes;

use StackSite\Core\Template;
use StackSite\Router\Route;

class UserRoute extends Route {

    public function register(): void
    {
        $this->router->add('/user', $this);
    }

    public function handle(): void
    {
        Template::getHeader("StackSats ~ User", []);

        require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/user_page.php';

        Template::getFooter();
    }
}