<?php

namespace StackSite\PushSubscriptions;

readonly class PushSubscriptionsValidator
{
    public function __construct(
        private PushSubscriptions $subscription,
    )
    {
    }

    public function validateEndpoint(string $endpoint): bool
    {
        return filter_var($endpoint, FILTER_VALIDATE_URL) !== false;
    }

    public function validateBase64UrlSafe(string $key): bool
    {
        return preg_match('/^[A-Za-z0-9\-_+\/]+={0,2}$/', $key);
    }

    public function validateSubscription(): bool
    {
        if (isset($this->subscription) === false) {
            return false;
        }

        if ($this->validateEndpoint($this->subscription->getEndpoint()) === false) {
            return false;
        }

        if ($this->validateBase64UrlSafe($this->subscription->getP256dh()) === false) {
            return false;
        }

        if ($this->validateBase64UrlSafe($this->subscription->getAuth()) === false) {
            return false;
        }

        $pushSubscriptionsService = new PushSubscriptionsService();
        $payload                  = json_encode(
            [
                'title' => 'Hello Crypto-king',
                'body' => 'This is a test message to make sure you are getting the message.',
                'icon' => '/icons/favicon-96x96.png',
                'url' => '/'
            ]);

        return $pushSubscriptionsService->sendNotification($this->subscription, $payload);
    }
}
