<?php

namespace StackSite\Router\Routes;

use StackSite\Core\Template;
use StackSite\Router\Route;

class VerifyRoute extends Route {

    public function register(): void
    {
        $this->router->add('/user/verify', $this);
    }

    public function handle(): void
    {
        Template::getHeader("StackSats ~ Verify User", []);

        require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/verify_page.php';

        Template::getFooter();
    }
}