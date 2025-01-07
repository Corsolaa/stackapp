<?php

namespace StackSite\Router\Routes;

use StackSite\Core\Template;
use StackSite\Router\Route;

class HomeRoute extends Route {

    public function register(): void
    {
        $this->router->add('/', $this);
    }

    public function handle(): void
    {
        Template::getHeader("Welcome!!!", ['home_page.css']);

        require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/home_page.php';

        Template::getFooter();
    }
}