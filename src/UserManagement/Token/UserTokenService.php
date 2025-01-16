<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

use StackSite\Core\Exceptions\TemplateNotFoundException;
use StackSite\Core\Mailing\EmailHandler;
use StackSite\Core\Mailing\Template\EmailTemplateService;
use StackSite\UserManagement\Token\Mailing\TokenMailingServiceInterface;
use StackSite\UserManagement\User;

readonly class UserTokenService
{
    private EmailHandler $emailHandler;

    public function __construct(
        private TokenPersistence             $tokenPersistence,
        private EmailTemplateService         $emailTemplateService,
        private TokenMailingServiceInterface $tokenMailingService,
    ) {
        $this->emailHandler = new EmailHandler($_ENV['NOREPLY_MAILADRES'], $_ENV['NOREPLY_FROM_NAME']);
    }

    public function processUserVerifyToken(User $user, Token $token): bool
    {
        $this->tokenPersistence
            ->setToken($token)
            ->upload();

        try {
            $body = $this->emailTemplateService->renderTemplateByName(
                'user_verify_token',
                ['TOKEN' => $token->getToken(),]
            );
        } catch (TemplateNotFoundException) {
            return false;
        }

        $this->emailHandler->setRecipient($user->getEmail());

        return $this->emailHandler->send('Verify your StackSats account!', $body);
    }

    public function processUserConfirmToken(User $user, Token $token): bool
    {
        $this->tokenPersistence
            ->deleteById($token->getId());

        return $this->tokenMailingService->send($token, $user->getEmail());
    }

    public function processUserLoginToken(Token $token): bool
    {
        $result = $this->tokenPersistence->setToken($token)
            ->upload();
        return $result > 0;
    }

    public function processUserPasswordResetToken(User $user, Token $token): bool
    {
        $this->tokenPersistence
            ->setToken($token)
            ->upload();

        return $this->tokenMailingService->send($token, $user->getEmail());
    }
}