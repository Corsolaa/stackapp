<?php

declare(strict_types=1);

namespace StackSite\UserManagement\Token;

use StackSite\Core\Exceptions\TemplateNotFoundException;
use StackSite\Core\Mailing\EmailHandler;
use StackSite\Core\Mailing\Template\EmailTemplateService;
use StackSite\UserManagement\User;
use StackSite\UserManagement\UserPersistence;

readonly class UserTokenService
{
    private EmailHandler $emailHandler;

    public function __construct(
        private TokenPersistence     $tokenPersistence,
        private EmailTemplateService $emailTemplateService,
        private TokenValidator       $tokenValidator,
        private UserPersistence      $userPersistence,
    ) {
        $this->emailHandler = new EmailHandler($_ENV['NOREPLY_MAILADRES'], $_ENV['NOREPLY_FROM_NAME']);
    }

    public function processUserVerifyToken(User $user, Token $token): bool
    {
        $this->tokenPersistence->upload($token);

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

    public function processUserPasswordResetToken(User $user, Token $token): bool
    {
        $this->tokenPersistence->upload($token);

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
        $this->tokenPersistence->deleteAllTokensByUserIdAndType($user->getId(), TOKEN::LOGIN);

        try {
            $template = $this->emailTemplateService->renderTemplateByName('user_password_reset_success');
        } catch (TemplateNotFoundException) {
            return false;
        }

        $this->emailHandler->setRecipient($user->getEmail());

        return $this->emailHandler->send($template->getSubject(), $template->getContent());
    }

    public function getUserByToken(Token $token): ?User
    {
        $resultToken = $this->tokenPersistence->fetchByTokenAndType($token);

        if ($resultToken === null || $this->tokenValidator->isExpired($resultToken)) {
            return null;
        }

        return $this->userPersistence->fetchByUserId($resultToken->getUserId());
    }
}