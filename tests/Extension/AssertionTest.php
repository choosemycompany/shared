<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Extension;

use Assert\AssertionFailedException;
use ChooseMyCompany\Shared\Extension\Assert\Assertion;
use PHPUnit\Framework\TestCase;

final class AssertionTest extends TestCase
{
    public function testThatSatisfyIdentifier(): void
    {
        // Given
        $givenValue = '1';
        $givenIdentifierFactory = static fn ($value) => $value;
        $givenExistenceCheck = static fn ($value) => $value === '1';

        // When
        $actualResult = Assertion::satisfyIdentifier($givenValue, $givenIdentifierFactory, $givenExistenceCheck);

        // Then
        self::assertTrue($actualResult);
    }

    public static function provideInvalidIdentifiers(): \Generator
    {
        yield 'Empty string' => [
            '',
            static fn ($value) => $value,
        ];

        yield 'Non stringable' => [
            false,
            static fn ($value) => $value,
        ];

        yield 'Invalid value' => [
            '1',
            static fn ($value) => null,
        ];

        yield 'Non existant value' => [
            '2',
            static fn ($value) => $value,
        ];
    }

    /**
     * @dataProvider provideInvalidIdentifiers
     */
    public function testThatSatisfyIdentifierThrowException(mixed $givenValue, callable $givenIdentifierFactory): void
    {
        // Given
        $givenExistenceCheck = static fn ($value) => $value === '1';

        // Then
        $this->expectException(AssertionFailedException::class);

        // When
        Assertion::satisfyIdentifier($givenValue, $givenIdentifierFactory, $givenExistenceCheck);
    }

    public function testThatAllSatisfyIdentifier(): void
    {
        // Given
        $givenValues = ['1', '1'];
        $givenIdentifierFactory = static fn ($value) => $value;
        $givenExistenceCheck = static fn ($value) => $value === '1';

        // When
        $actualResult = Assertion::allSatisfyIdentifier($givenValues, $givenIdentifierFactory, $givenExistenceCheck);

        // Then
        self::assertTrue($actualResult);
    }

    public function testThatStringable(): void
    {
        // Given
        $givenValue = '1';

        // When
        $actualResult = Assertion::stringable($givenValue);

        // Then
        self::assertTrue($actualResult);
    }

    public function testThatAllStringable(): void
    {
        // Given
        $givenValue = ['1', 2];

        // When
        $actualResult = Assertion::allStringable($givenValue);

        // Then
        self::assertTrue($actualResult);
    }
}
