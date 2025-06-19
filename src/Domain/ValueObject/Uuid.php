<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use Assert\Assert;

final class Uuid implements \Stringable
{
    public function __construct(
        private readonly string $value,
    ) {
    }

    public static function from(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        self::assertValid($value);

        return new self($value);
    }

    private static function assertValid(mixed $value): void
    {
        Assert::that($value)
            ->string('value must be a string')
            ->uuid('value must be a valid UUID');
    }

    public static function validate(mixed $value): ?string
    {
        try {
            self::assertValid($value);
            return null;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
