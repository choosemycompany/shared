<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use Assert\Assert;

final class ErrorLogMessage
{
    private function __construct(
        private readonly string $value,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function from(string $value): self
    {
        Assert::that($value)->notBlank('Error log message must not be blank.');

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
