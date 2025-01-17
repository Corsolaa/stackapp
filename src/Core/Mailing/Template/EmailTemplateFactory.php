<?php

declare(strict_types=1);

namespace StackSite\Core\Mailing\Template;

class EmailTemplateFactory
{
    public function fromArray(array $data): EmailTemplate
    {
        return new EmailTemplate(
            isset($data['id']) ? (int)$data['id'] : null,
            $data['name'] ?? '',
            $data['subject'] ?? '',
            $data['content'] ?? '',
            isset($data['created_at']) ? (int)$data['created_at'] : 0,
        );
    }
}