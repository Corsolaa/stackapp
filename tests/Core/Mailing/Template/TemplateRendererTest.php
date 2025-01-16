<?php

namespace Core\Mailing\Template;

use StackSite\Core\Mailing\Template\TemplateRenderer;
use PHPUnit\Framework\TestCase;

class TemplateRendererTest extends TestCase
{
    private TemplateRenderer $templateRenderer;

    public function setUp(): void
    {
        parent::setUp();

        $this->templateRenderer = new TemplateRenderer();
    }

    public function testRenderSuccessfully(): void
    {
        $variables = [
            'USER_EMAIL' => 'test@stacksats.ai',
            'TOKEN' => '298734928734'
        ];

        $testTemplate = 'Hello {{USER_EMAIL}},\n\nYour token is {{TOKEN}}.\n\nBest regards,\n{{COMPANY_NAME}}';
        $expected = 'Hello test@stacksats.ai,\n\nYour token is 298734928734.\n\nBest regards,\n';

        $result = $this->templateRenderer->render($testTemplate, $variables);

        $this->assertSame($expected, $result);
    }
}
