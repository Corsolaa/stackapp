<?php

namespace StackSite\Router\Routes;

use DeviceDetector\DeviceDetector;
use StackSite\Core\Http\SessionHub;
use StackSite\Core\Template;
use StackSite\Router\Route;
use StackSite\UserManagement\Factories\UserControllerFactory;

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
//                $userAgent = $_SERVER['HTTP_USER_AGENT'];
//                $dd = new DeviceDetector($userAgent);
//                $dd->parse();
//
//                $clientInfo = $dd->getClient();    // Informatie over browser, feed reader, media player, etc.
//                $osInfo = $dd->getOs();            // Informatie over het besturingssysteem
//                $device = $dd->getDeviceName();    // Naam van het apparaat
//                $brand = $dd->getBrandName();      // Merk van het apparaat
//                $model = $dd->getModel();          // Model van het apparaat
//
//                $deviceInfo = [
//                    'Browser/Client'     => $clientInfo,
//                    'Besturingssysteem'  => $osInfo,
//                    'Apparaatnaam'       => $device,
//                    'Merk'               => $brand,
//                    'Model'              => $model
//                ];
//
//                echo "<pre>";
//                var_dump($deviceInfo);
//                echo "</pre>";

                $user = (UserControllerFactory::create())->getUserBySessionToken();

                if ($user != null) {
                    header("Location: /user");
                    return;
                }

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
                SessionHub::logout();
                header("Location: /");
                break;


            default:
                if (SessionHub::getToken() !== '') {
                    $user = (UserControllerFactory::create())->getUserBySessionToken();

                    if ($user === null) {
                        SessionHub::logout();
                        header("Location: /user?login");
                        return;
                    }

                    require $_SERVER['DOCUMENT_ROOT'] . '/src/Views/user_page.php';
                    return;
                };

                header("Location: /user?login");
        }
    }
}