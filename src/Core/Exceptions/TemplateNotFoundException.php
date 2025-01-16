<?php

declare(strict_types=1);

namespace StackSite\Core\Exceptions;

class TemplateNotFoundException extends StackException
{
    public function __construct(protected readonly string $templateName, $message = "Template not found.")
    {
        $message = "{$message} ~ TemplateName: {$this->templateName}";

        parent::__construct($message, 678);

        $this->sendErrorEmail();
    }

    private function sendErrorEmail(): void
    {
        $body = "An exception occurred:<br><br>" .
            "Message: {$this->getMessage()}<br>" .
            "Templatename: $this->templateName<br>" .
            "File: {$this->getFile()}<br>" .
            "Line: {$this->getLine()}<br>" .
            "Trace: {$this->getTraceAsString()}";

        $this->emailHandler->send('Critical Error: Template name not found', $body);
    }
}