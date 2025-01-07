<?php

namespace StackSite\Router\Routes;

use ErrorException;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use StackSite\Core\Template;
use StackSite\Router\Route;

class TestRoute extends Route {

    public function register(): void
    {
        $this->router->add('/test', $this);
    }

    /**
     * @throws ErrorException
     */
    public function handle(): void
    {
        Template::getHeader("StackSats ~ TEST", []);

        $publicKey = $_ENV['PUBLIC_VALID_KEY'];
        $privateKey = $_ENV['PRIVATE_VAPID_KEY'];
        $endpointUrl = '';

        $subscription = new Subscription(
            $endpointUrl,
            $publicKey,
            $privateKey
        );

        $webPush = new WebPush([
                                   'VAPID' => [
                                       'subject' => 'mailto: <info@stacksats.ai>',
                                       'publicKey' => $publicKey,
                                       'privateKey' => $privateKey,
                                   ],
                               ]);

        $payload = json_encode([
                                   'title' => 'Hello!',
                                   'body' => 'This is a push notification from PHP.',
                                   'icon' => '/icon.png',
                               ]);

        $result = $webPush->sendOneNotification($subscription, $payload);

        if ($result->isSuccess()) {
            echo "Push notification sent successfully!";
        } else {
            echo "Failed to send push notification: " . $result->getReason();
        }

        Template::getFooter();
    }
}