<?php

declare(strict_types=1);

namespace StackSite\Core\Exceptions;

use Exception;
use StackSite\Core\Mailing\EmailHandler;

class StackException Extends Exception
{
    protected EmailHandler $emailHandler;

    public function __construct(string $message, int $code)
    {
        $this->emailHandler = new EmailHandler($_ENV['ERROR_MAILADRES'], $_ENV['ERROR_FROM_NAME']);
        $this->emailHandler->setRecipient($_ENV['ADMIN_MAILADRES']);

        parent::__construct($message, $code);
    }
}