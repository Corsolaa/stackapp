<?php

namespace StackSite\PushSubscriptions;

use StackSite\Core\HttpErrors;
use StackSite\Core\Mailing\EmailHandler;
use StackSite\Core\RequestBodyHandler;

class PushSubscriptionsHandler
{
    public function __construct(
        private ?RequestBodyHandler $requestBody = null
    )
    {
        $this->requestBody = $this->requestBody ?? new RequestBodyHandler();
    }

    public function registerSubscription(): bool
    {
        // TODO check user is logged in and set the userId in the PushSubscriptions
        $user_id   = $this->requestBody->get('user_id');
        $json_data = $this->requestBody->getAll(['user_id', 'name', 'endpoint', 'p256dh', 'auth']);

        if ($this->requestBody->checkFilledBody($json_data) === false) {
            HttpErrors::badRequest();
        }

        $httpSubscription = new PushSubscriptions(
            null,
            $user_id,
            $json_data['name'],
            $json_data['endpoint'],
            $json_data['p256dh'],
            $json_data['auth']
        );

        if (PushSubscriptionsPersistence::fetchByEndpoint($httpSubscription->getEndpoint())) {
            return false;
        }

        // TODO, continue from here.
        $subscriptionValidator = new PushSubscriptionsValidator($httpSubscription);

        if ($subscriptionValidator->validateSubscription() === false) {
            return false;
        }

        $id = (new PushSubscriptionsPersistence($httpSubscription))->upload();
        $httpSubscription->setId($id);

        $emailHandler = new EmailHandler('register@stacksats.ai', 'Push Register');
        $emailHandler->setRecipient($_ENV['ADMIN_MAILADRES']);
        $emailHandler->send('Stacksite ~ somebody or something registered to the push notifications',
                            '<pre>' . json_encode($httpSubscription->toArray()) . '</pre>');

        return true;
    }

    public function sendMessageToLatest(): bool
    {
        $latestSubscription = PushSubscriptionsPersistence::fetchLatest();

        if ($latestSubscription->getId() === null) {
            return false;
        }

        $pushSubscriptionsService = new PushSubscriptionsService();
        $payload                  = json_encode(
            [
                'title' => 'Hello Crypto-king',
                'body' => 'This is a push notification from StackSats.',
                'icon' => '/icons/favicon-96x96.png',
                'url' => '/'
            ]);

        return $pushSubscriptionsService->sendNotification($latestSubscription, $payload);
    }
}
