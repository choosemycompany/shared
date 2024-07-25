<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use ChooseMyCompany\Shared\Extension\Assert\Assert;

abstract class LegacyIdentifier implements \Stringable
{
    final private function __construct(private readonly string $value)
    {
        Assert::that($value)
            ->notBlank()
            ->integerish($value)
            ->greaterThan(0)
        ;
    }

    public static function from(string $value): static
    {
        return new static($value);
    }

    public static function tryFrom(string $value): ?static
    {
        try {
            return new static($value);
        } catch (\Throwable) {
            return null;
        }
    }

    public function toInteger(): int
    {
        return (int) $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
