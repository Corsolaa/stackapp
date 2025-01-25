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

        echo "User page";

        Template::getFooter();
    }
}