<?php

namespace StackSite\Core\Exceptions;

use Exception;
use StackSite\Core\Mailing\EmailHandler;

class ObjectNotSetException extends Exception
{
    private string $variableName;

    public function __construct(string $variableName, $message = "Object is not set.")
    {
        $this->variableName = $variableName;
        $message = "{$message} Variable: {$variableName}";

        $this->sendErrorEmail();

        parent::__construct($message, 422);
    }

    private function sendErrorEmail(): void
    {
        $emailHandler = new EmailHandler($_ENV['ERROR_MAILADRES'], $_ENV['ERROR_FROM_NAME']);
        $emailHandler->setRecipient($_ENV['ADMIN_MAILADRES']);

        $body = "An exception occurred:\n\n" .
            "Message: {$this->getMessage()}\n" .
            "Variable: {$this->variableName}\n" .
            "File: {$this->getFile()}\n" .
            "Line: {$this->getLine()}\n" .
            "Trace: {$this->getTraceAsString()}";

        $emailHandler->send('Critical Error: Object Not Set', $body);
    }
}
