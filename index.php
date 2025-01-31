<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use StackSite\Core\RequestBodyHandler;
use StackSite\Router\Router;
use Dotenv\Dotenv;

session_start();

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$requestBodyHandler = new RequestBodyHandler();
$requestBodyHandler->loadBody();

$router = new Router();

foreach (glob(__DIR__ . '/src/Router/Routes/*.php') as $file) {
    $className = 'StackSite\\Router\\Routes\\' . basename($file, '.php');
    if (class_exists($className)) {
        new $className($router);
    }
}

$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($requestedPath);