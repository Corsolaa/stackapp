<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token\Mailing;

use StackSite\Core\Mailing\EmailHandler;
use StackSite\UserManagement\Token\Token;

class VerifyTokenMailingService extends TokenMailingServiceInterface
{
    public function __construct(
        private readonly EmailHandler $emailHandler
    ) {
    }

    public function send(Token $token, string $email): bool
    {
        $this->emailHandler->setRecipient($email);

        $body = "
            <h1>Email account verification</h1>
            <br><br>
            <p>Here is the link to click to verify your account, it doesn't expire.</p>
            <br>
            <a href='https://app.stacksats.ai/user?verify=" . $token->getToken() . "'>Verification Link</a>
        ";

        return $this->emailHandler->send('Verify your StackSats account!', $body);
    }
}