<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use Assert\Assert;
use Assert\Assertion;

abstract class Identifier implements \Stringable
{
    final public function __construct(
        private readonly Uuid $uuid,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function from(mixed $value): static
    {
        return match (true) {
            $value instanceof self => $value,
            $value instanceof Uuid => new static($value),
            \is_string($value) => static::fromString($value),
            default => throw new \InvalidArgumentException('Invalid value type for Identifier.'),
        };
    }

    private static function fromString(string $value): static
    {
        self::assertValid($value);

        return new static(Uuid::from($value));
    }

    public static function tryFrom(string $value): ?static
    {
        try {
            return static::from($value);
        } catch (\Throwable) {
            return null;
        }
    }

    public function equals(self $other): bool
    {
        $staticClass = static::class;
        $otherClass = $other::class;

        Assertion::same(
            $staticClass,
            $otherClass,
            \sprintf(
                'Both identifiers must be of the same class to be compared. Expected %s, but got %s.',
                $staticClass,
                $otherClass
            )
        );

        return $other->toString() === $this->toString();
    }

    public static function validate(mixed $value): bool|string
    {
        try {
            self::assertValid($value);
            return true;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    private static function assertValid(mixed $value): void
    {
        Assert::that($value)
            ->string('identifier must be a string')
            ->uuid('identifier must be a valid UUID');
    }

    public function toUuid(): Uuid
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
