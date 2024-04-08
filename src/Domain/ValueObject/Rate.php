<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use Assert\Assert;

final readonly class Rate
{
    private function __construct(private float $value)
    {
    }

    public static function createFromFloat(float $value): self
    {
        Assert::that($value)->between(0, 1);

        return new self($value);
    }

    public static function createFromFraction(int $numerator, int $denominator): self
    {
        if (0 === $numerator && 0 === $denominator) {
            return new self(0.0);
        }

        Assert::lazy()
            ->that($numerator)
            ->greaterOrEqualThan(0)
            ->that($denominator)
            ->greaterThan(0, 'Division by zero is not allowed.')
            ->verifyNow()
        ;
        Assert::that($denominator)->greaterOrEqualThan($numerator);

        $value = (float) \bcdiv((string) $numerator, (string) $denominator, 3);

        return new self($value);
    }

    public static function createFromPercentage(float $percentage): Rate
    {
        Assert::that($percentage)->between(0, 100);

        return new self((float) \bcdiv((string) $percentage, '100', 3));
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function toPercentage(): float
    {
        return (float) \bcmul((string) $this->value, '100', 1);
    }

    public function getRoundedValue(int $precision = 2): float
    {
        return \round($this->value, $precision);
    }
}
