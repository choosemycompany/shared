<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use ChooseMyCompany\Shared\Extension\Assert\Assertion;

abstract class Identifier implements \Stringable
{
    final private function __construct(private readonly Uuid $uuid)
    {
    }

    public static function fromUuid(Uuid $value): static
    {
        return new static($value);
    }

    public static function fromString(string $value): static
    {
        return new static(Uuid::fromString($value));
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function equalsTo(Identifier $other): bool
    {
        $otherClass = $other::class;
        $staticClass = static::class;

        Assertion::same($otherClass, $staticClass, \sprintf('Both identifiers must be of the same class to be compared. Expected %s, but got %s.', $staticClass, $otherClass));

        return (string)$other === (string)$this;
    }

    public function __toString(): string
    {
        return (string)$this->uuid;
    }
}
