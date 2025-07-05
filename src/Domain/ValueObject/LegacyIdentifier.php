<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use Assert\Assert;
use Assert\Assertion;

abstract class LegacyIdentifier implements \Stringable
{
    final public function __construct(
        private readonly string $value,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function from(mixed $value): static
    {
        return match (true) {
            $value instanceof self => $value,
            \is_string($value), \is_int($value) => static::fromString(\strval($value)),
            default => throw new \InvalidArgumentException('Invalid value type for Identifier.'),
        };
    }

    private static function fromString(string $value): static
    {
        self::assertValid($value);

        return new static($value);
    }

    public static function tryFrom(mixed $value): ?static
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

        return $other->value === $this->value;
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
            ->integerish($value, 'identifier must be an integer')
            ->greaterThan(0, 'identifier must be a positive integer');
    }

    public function toInteger(): int
    {
        return \intval($this->value);
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
