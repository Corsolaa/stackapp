<?php

namespace StackSite\Router;

abstract class Route {
    protected Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
        $this->register();
    }

    abstract public function register(): void;
    abstract public function handle(): void;
}