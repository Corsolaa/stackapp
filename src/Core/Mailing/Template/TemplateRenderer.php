<?php

declare(strict_types=1);

namespace StackSite\Core\Mailing\Template;

class TemplateRenderer
{
    public function render(string $template, array $variables = []): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace("{{" . $key . "}}", $value, $template);
        }

        return preg_replace('/{{\w+}}/', '', $template);
    }
}