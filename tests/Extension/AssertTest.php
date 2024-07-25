<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Extension;

use ChooseMyCompany\Shared\Domain\ValueObject\Result\ValidationResult;
use ChooseMyCompany\Shared\Extension\Assert\Assert;
use PHPUnit\Framework\TestCase;

final class AssertTest extends TestCase
{
    public function testThatSatisfyIdentifierOnLazy(): void
    {
        // Given
        $givenValue = '1';
        $givenIdentifierFactory = static fn ($value) => $value;
        $givenExistenceCheck = static fn ($value) => $value === '1';

        // When
        $actualResult = Assert::lazy()
            ->that($givenValue)
            ->satisfyIdentifier($givenIdentifierFactory, $givenExistenceCheck)
            ->verifyNow();

        // Then
        self::assertTrue($actualResult);
    }

    public function testThatStringableOnLazy(): void
    {
        // Given
        $givenValue = '1';

        // When
        $actualResult = Assert::lazy()
            ->that($givenValue)
            ->stringable()
            ->verifyNow();

        // Then
        self::assertTrue($actualResult);
    }

    public function testThatValidateAndReturnResultOnLazy(): void
    {
        // Given
        $givenValue = '1';

        // When
        $actualResult = Assert::lazy()
            ->that($givenValue)
            ->same('1')
            ->validateAndReturnResult();

        // Then
        self::assertInstanceOf(ValidationResult::class, $actualResult);
    }
}
