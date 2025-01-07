<?php

namespace StackSite\PushSubscriptions;

use StackSite\Core\SqlHandler;

class PushSubscriptionsPersistence
{
    private PushSubscriptions $subscription;

    public function __construct(PushSubscriptions $subscription)
    {
        $this->subscription = $subscription;
    }

    public function upload(): int
    {
        $query = "INSERT INTO push_subscriptions (
                                user_id,
                                name,
                                endpoint,
                                p256dh,
                                auth,
                                created_at) 
                  VALUES (
                          '" . SqlHandler::cleanString($this->subscription->getUserId()) . "',
                          '" . SqlHandler::cleanString($this->subscription->getName()) . "',
                          '" . SqlHandler::cleanString($this->subscription->getEndpoint()) . "',
                          '" . SqlHandler::cleanString($this->subscription->getP256dh()) . "',
                          '" . SqlHandler::cleanString($this->subscription->getAuth()) . "',
                          " . time() . ")";

        return SqlHandler::insert($query);
    }

    public static function fetchLatest(): PushSubscriptions
    {
        $query = "SELECT * FROM push_subscriptions
                    ORDER BY created_at DESC
                    LIMIT 1";

        return PushSubscriptions::fromArray(SqlHandler::fetch($query)[0]);
    }

    public static function fetchAll(): array
    {
        $query = "SELECT * FROM push_subscriptions
                    ORDER BY created_at DESC";

        $result        = SqlHandler::fetch($query);
        $notifications = [];

        foreach ($result as $row) {
            $notifications[] = PushSubscriptions::fromArray($row);
        }

        return $notifications;
    }

    public static function fetchByEndpoint(string $endpoint): ?PushSubscriptions
    {
        $query = "SELECT * FROM push_subscriptions
         WHERE endpoint = '" . SqlHandler::cleanString($endpoint) . "'
         LIMIT 1";

        $result = SqlHandler::fetch($query);

        return !empty($result) ? PushSubscriptions::fromArray($result[0]) : null;
    }
}
