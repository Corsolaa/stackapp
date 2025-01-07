<?php

namespace StackSite\PushSubscriptions;

use ErrorException;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use StackSite\Core\Mailing\StandardMails;

class PushSubscriptionsService
{
    private WebPush $webPush;

    public function __construct()
    {
        try {
            $this->webPush = new WebPush(
                [
                    'VAPID' => [
                        'subject' => 'mailto:' . $_ENV['INFO_MAILADRES'],
                        'publicKey' => $_ENV['PUBLIC_VALID_KEY'],
                        'privateKey' => $_ENV['PRIVATE_VAPID_KEY'],
                    ]
                ]);
        } catch (ErrorException $e) {
            StandardMails::apiHandleFailed(
                'webPush creation is broken',
                'Something went wrong while creating webPush in PushSubscriptionsService.<br><br>' .
                '<strong>error message: </strong><br><i>' . $e->getMessage() . '</i>');
        }
    }

    public function sendNotification(PushSubscriptions $subscriptionData, string $payload): bool
    {
        try {
            $webPushSubscription = Subscription::create(
                [
                    'endpoint' => $subscriptionData->getEndpoint(),
                    'keys' => [
                        'p256dh' => $subscriptionData->getP256dh(),
                        'auth' => $subscriptionData->getAuth(),
                    ],
                ]);

            $result = $this->webPush->sendOneNotification($webPushSubscription, $payload);

            if ($result->isSuccess() === false) {
                throw new ErrorException('Notification failed to send, probably because of faulty values');
            }

            return true;

        } catch (ErrorException $e) {
            StandardMails::apiHandleFailed(
                'sendNotification is broken',
                'Something went wrong while sending a push notification.<br><br>' .
                '<strong>error message: </strong><br><i>' . $e->getMessage() . '</i><br><br>' .
                '<strong>Push Subscribtion data: </strong><pre>' . json_encode($subscriptionData->toArray()) . '</pre><br><br>' .
                '<strong>Payload data: </strong><pre>' . $payload . '</pre>');
        }
        return false;
    }
}