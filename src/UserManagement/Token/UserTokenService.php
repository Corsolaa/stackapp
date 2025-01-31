<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

use StackSite\Core\Exceptions\TemplateNotFoundException;
use StackSite\Core\Mailing\EmailHandler;
use StackSite\Core\Mailing\Template\EmailTemplateService;
use StackSite\UserManagement\User;

readonly class UserTokenService
{
    private EmailHandler $emailHandler;

    public function __construct(
        private TokenPersistence     $tokenPersistence,
        private EmailTemplateService $emailTemplateService
    ) {
        $this->emailHandler = new EmailHandler($_ENV['NOREPLY_MAILADRES'], $_ENV['NOREPLY_FROM_NAME']);
    }

    public function processUserVerifyToken(User $user, Token $token): bool
    {
        $this->tokenPersistence
            ->setToken($token)
            ->upload();

        try {
            $template = $this->emailTemplateService->renderTemplateByName(
                'user_verify_token',
                ['TOKEN' => $token->getToken()]
            );
        } catch (TemplateNotFoundException) {
            return false;
        }

        $this->emailHandler->setRecipient($user->getEmail());

        return $this->emailHandler->send($template->getSubject(), $template->getContent());
    }

    public function processUserConfirmToken(User $user, Token $token): bool
    {
        $this->tokenPersistence->deleteById($token->getId());

        try {
            $template = $this->emailTemplateService->renderTemplateByName('user_verify_success');
        } catch (TemplateNotFoundException) {
            return false;
        }

        $this->emailHandler->setRecipient($user->getEmail());

        return $this->emailHandler->send($template->getSubject(), $template->getContent());
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

        try {
            $template = $this->emailTemplateService->renderTemplateByName(
                'user_password_reset',
                ['TOKEN' => $token->getToken()]
            );
        } catch (TemplateNotFoundException) {
            return false;
        }

        $this->emailHandler->setRecipient($user->getEmail());

        return $this->emailHandler->send($template->getSubject(), $template->getContent());
    }

    public function processUserConfirmPasswordResetToken(User $user, Token $token): bool
    {
        $this->tokenPersistence->deleteById($token->getId());

        try {
            $template = $this->emailTemplateService->renderTemplateByName('user_password_reset_success');
        } catch (TemplateNotFoundException) {
            return false;
        }

        $this->emailHandler->setRecipient($user->getEmail());

        return $this->emailHandler->send($template->getSubject(), $template->getContent());
    }

}