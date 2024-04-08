<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\Score\ValueObject;

use ChooseMyCompany\Shared\Domain\ValueObject\Rate;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RateTest extends TestCase
{
    public function testThatCreatesFromFloatSuccessfully(): void
    {
        // Given
        $givenValue = 0.5;

        // When
        $sut = Rate::createFromFloat($givenValue);

        // Then
        self::assertSame(50.0, $sut->toPercentage());
    }

    public function testThatCreatesFromFloatFailed(): void
    {
        // Given
        $givenValue = 2;

        // Then
        $this->expectException(\InvalidArgumentException::class);

        // When
        Rate::createFromFloat($givenValue);
    }

    public function testThatCreatesFromFractionSuccessfully(): void
    {
        // Given
        $givenNumerator = 50;
        $givenDenominator = 100;

        // When
        $sut = Rate::createFromFraction($givenNumerator, $givenDenominator);

        // Then
        self::assertSame(50.0, $sut->toPercentage());
    }

    public static function provideFloorSuccessfullyData(): \Generator
    {
        yield [1, 3, 33.3, 0.333];

        yield [11, 150, 7.3, 0.073];

        yield [9, 21, 42.8, 0.428];
    }

    #[DataProvider('provideFloorSuccessfullyData')]
    public function testThatFloorSuccessfully(
        int $givenNumerator,
        int $givenDenominator,
        float $expectedPercentageResult,
        float $expectedValueResult
    ): void {
        // When
        $sut = Rate::createFromFraction($givenNumerator, $givenDenominator);

        // Then
        self::assertSame($expectedPercentageResult, $sut->toPercentage());
        self::assertSame($expectedValueResult, $sut->getValue());
    }

    public function testThatCreatesFromFractionWithNumeratorEqualsZero(): void
    {
        // Given
        $givenNumerator = 0;
        $givenDenominator = 100;

        // When
        $sut = Rate::createFromFraction($givenNumerator, $givenDenominator);

        // Then
        self::assertSame(0.0, $sut->toPercentage());
    }

    public function testThatReturnsZeroWhenZerosGiven(): void
    {
        // When
        $actualResult = Rate::createFromFraction(0, 0);

        // Then
        self::assertSame(0.0, $actualResult->getValue());
    }

    /**
     * @param mixed $givenDenominator
     */
    #[DataProvider('provideFailedDataToCreateFromFraction')]
    public function testThatFailedToCreateFromFraction(int $givenNumerator, int $givenDenominator): void
    {
        // Then
        $this->expectException(\InvalidArgumentException::class);

        // When
        Rate::createFromFraction($givenNumerator, $givenDenominator);
    }

    public static function provideFailedDataToCreateFromFraction(): \Generator
    {
        yield 'Numerator greater than denominator' => [100, 50];

        yield 'Numerator negative' => [-1, 100];

        yield 'Denominator negative' => [50, -1];

        yield 'Denominator equals zero' => [50, 0];
    }

    public function testCreateFromPercentageSuccessfully(): void
    {
        // Given
        $givenPercentage = 12.123;

        // When
        $actualResult = Rate::createFromPercentage($givenPercentage);

        // Then
        self::assertSame(0.121, $actualResult->getValue());
    }

    public function testCreateFromPercentageFailed(): void
    {
        // Given
        $givenPercentage = 101;

        // Then
        $this->expectException(\InvalidArgumentException::class);

        // When
        Rate::createFromPercentage($givenPercentage);
    }

    #[DataProvider('provideRoundValueData')]
    public function testThatReturnsRoundValue(float $givenValue, float $expectedRoundValue): void
    {
        // Given
        $sut = Rate::createFromFloat($givenValue);

        // When
        $actualResult = $sut->getRoundedValue();

        // Then
        self::assertSame($actualResult, $expectedRoundValue);
    }

    public static function provideRoundValueData(): \Generator
    {
        yield [0.123, 0.12];

        yield [0.1234, 0.12];
    }
}
