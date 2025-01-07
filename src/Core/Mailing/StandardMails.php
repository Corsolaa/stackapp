<?php

namespace StackSite\Core\Mailing;

use StackSite\Core\RequestBodyHandler;

class StandardMails
{
    private static EmailHandler $emailHandler;

    public static function createEmailHandler(): void
    {
        if (isset(self::$emailHandler)) {
            return;
        }

        self::$emailHandler = new EmailHandler($_ENV['DEBUG_MAILADRES'], $_ENV['DEBUG_FROM_NAME']);
        self::$emailHandler->setRecipient($_ENV['ADMIN_MAILADRES']);
    }

    public static function apiHandleFailed($title, $top_message, $include_api_body = 1): void
    {
        self::createEmailHandler();

        $message = $top_message . '<br><br>';

        if ($include_api_body) {
            $message .= '<strong>Api body:</strong><br><pre>' .
                json_encode(
                    (new RequestBodyHandler)->getAll(),
                    JSON_PRETTY_PRINT
                ) . '</pre>';
        }

        self::$emailHandler->send('API exception -> ' . $title, $message);
    }
}