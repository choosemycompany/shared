<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

abstract class Identifier
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

    public function __toString(): string
    {
        return (string) $this->uuid;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}
