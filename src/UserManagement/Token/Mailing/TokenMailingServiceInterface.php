<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token\Mailing;

use StackSite\UserManagement\Token\Token;

abstract class TokenMailingServiceInterface
{
    abstract protected function send(Token $token, string $email): bool;
}