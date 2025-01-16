<?php

declare(strict_types=1);

namespace StackSite\Core\Mailing\Template;

use StackSite\Core\SqlHandler;

readonly class TemplatePersistence
{
    public function __construct(
        private EmailTemplateFactory $emailTemplateFactory
    ) {
    }

    public function fetchTemplateByName(string $name): ?EmailTemplate
    {
        $query = "SELECT * FROM email_templates
                    WHERE name = '" . SqlHandler::cleanString($name) . "'
                    LIMIT 1";

        $result = SqlHandler::fetch($query);

        return !empty($result) ? $this->emailTemplateFactory->fromArray($result[0]) : null;
    }
}