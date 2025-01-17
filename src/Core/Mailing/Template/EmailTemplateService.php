<?php

declare(strict_types=1);

namespace StackSite\Core\Mailing\Template;

use StackSite\Core\Exceptions\TemplateNotFoundException;

readonly class EmailTemplateService
{
    public function __construct(
        private TemplateRenderer $templateRenderer,
        private TemplatePersistence $templatePersistence
    ) {
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function renderTemplateByName(string $name, array $variables = []): EmailTemplate
    {
        $template = $this->templatePersistence->fetchTemplateByName($name);

        if ($template === null) {
            throw new TemplateNotFoundException($name);
        }

        return $template->setContent($this->templateRenderer->render($template->getContent(), $variables));
    }
}