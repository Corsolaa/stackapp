<?php

namespace StackSite\Router\Routes;

use StackSite\Router\Route;
use StackSite\PushSubscriptions\PushSubscriptionsHandler;

class SubscriptionRoute extends Route
{
    public function register(): void
    {
        $this->router->add('/subscribe', $this);
    }

    public function handle(): void
    {
        $subscriptionHandler = new PushSubscriptionsHandler();

        if (isset($_GET['register'])) {
            $subscriptionHandler->registerSubscription();
            return;
        }

        if (isset($_GET['send'])) {
            echo "sending...<br>";
            $subscriptionHandler->sendMessageToLatest();
            return;
        }

        if (isset($_GET['test'])) {
            echo "testing...<br>";
            return;
        }

        $options = ['register', 'send', 'test'];

        echo "<h2>Subscribe page</h2> <br>";
        echo "options:";
        echo "<ul>";
        foreach ($options as $option) {
            echo "<li><a href='/subscribe?$option'>$option</a></li>";
        }
        echo "</ul>";
    }
}