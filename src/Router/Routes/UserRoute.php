<?php

namespace StackSite\Router\Routes;

use StackSite\Core\Http\SessionHub;
use StackSite\Core\Template;
use StackSite\Router\Route;
use StackSite\UserManagement\UserPersistence;

class UserRoute extends Route
{
    public function register(): void
    {
        $this->router->add('/user', $this);
    }

    public function handle(): void
    {
        switch (true) {
            case (empty($_GET['password_reset']) === false):
                Template::getHeader("StackSats ~ Password Reset", []);
                require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/password_reset_page.php';
                Template::getFooter(['user_management/password_reset.js']);
                break;


            case isset($_GET['verify']):
                require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/verify_page.php';
                break;


            case isset($_GET['login']):
                Template::getHeader("StackSats ~ Login", []);
                require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/login_page.php';
                Template::getFooter(
                    [
                        'user_management/register_user.js',
                        'user_management/login_user.js',
                        'user_management/user_form_selector.js'
                    ]);
                break;


            case isset($_GET['logout']):
                header("Location: /");
                break;

            default:
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                    $user = (new UserPersistence)->fetchByUserId($_SESSION['user_id']);

                    if ($user === null) {
                        SessionHub::logout();
                        return;
                    }

                    require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/user_page.php';
                    return;
                };

                header("Location: /user?login");
        }
    }
}