<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use Assert\Assert;

final class InfoLog
{
    private function __construct(
        private readonly string $message,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function from(string $message): self
    {
        Assert::that($message)->notBlank('Info log must not be blank.');

        return new self($message);
    }

    public function toString(): string
    {
        return $this->message;
    }
}
