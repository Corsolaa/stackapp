<?php

namespace StackSite\Router\Routes;

use StackSite\Core\Template;
use StackSite\Router\Route;

class AboutRoute extends Route {

    public function register(): void
    {
        $this->router->add('/about', $this);
    }

    public function handle(): void
    {
        Template::getHeader("StackSats ~ About", []);

        echo "About Page!";

        Template::getFooter();
    }
}