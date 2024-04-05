<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use App\Survey\Extension\Assert\Assertion;

final class Uuid implements \Stringable
{
    private function __construct(private readonly string $value)
    {
    }

    public static function fromString(string $value): self
    {
        Assertion::uuid($value);

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
