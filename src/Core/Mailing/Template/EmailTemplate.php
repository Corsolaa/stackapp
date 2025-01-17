<?php

declare(strict_types=1);

namespace StackSite\Core\Mailing\Template;

class EmailTemplate
{
    public function __construct(
        private readonly ?int   $id = null,
        private readonly string $name = '',
        private readonly string $subject = '',
        private string          $content = '',
        private readonly ?int   $created_at = null
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

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
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