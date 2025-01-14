<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token\Mailing;

use StackSite\Core\Mailing\EmailHandler;
use StackSite\UserManagement\Token\Token;

class ConfirmVerifyTokenMailingService extends TokenMailingServiceInterface
{
    public function __construct(
        private readonly EmailHandler $emailHandler
    ) {
    }

    public function send(Token $token, string $email): bool
    {
        $this->emailHandler->setRecipient($email);

        $body = "
        <h1>Email verification complete</h1>
        <br>
        <p>The email en verified for this account.</p>
        ";

        return $this->emailHandler->send('Verified successfully', $body);
    }
}