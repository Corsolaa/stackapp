<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token\Mailing;

use StackSite\Core\Mailing\EmailHandler;
use StackSite\UserManagement\Token\Token;

class PasswordResetTokenMailingService extends TokenMailingServiceInterface
{
    public function __construct(
        private readonly EmailHandler $emailHandler
    ) {
    }

    public function send(Token $token, string $email): bool
    {
        $this->emailHandler->setRecipient($email);

        $body = "
            <h1>Email reset token</h1>
            <br><br>
            <p>Here is the link to click to reset your account password.</p>
            <p>This token will exprire in 15 minutes for security reasons.</p>
            <br>
            <a href='https://app.stacksats.ai/user?password_reset=" . $token->getToken() . "'>Password reset link</a>
        ";

        return $this->emailHandler->send('StackSats account reset', $body);
    }
}