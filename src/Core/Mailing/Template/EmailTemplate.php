<?php

declare(strict_types=1);

namespace StackSite\Core\Mailing\Template;

readonly class EmailTemplate
{
    public function __construct(
        private ?int   $id = null,
        private string $name = '',
        private string $content = '',
        private ?int   $created_at = null
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): int
    {
        return $this->created_at;
    }
}