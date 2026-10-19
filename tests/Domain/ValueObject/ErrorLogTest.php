<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\ValueObject;

use ChooseMyCompany\Shared\Domain\ValueObject\ErrorLog;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ErrorLogTest extends TestCase
{
    public function testThatItRendersTheGivenMessage(): void
    {
        // Given
        $givenValue = 'Submission dropped for form 42: email: is required';

        // When
        $actualString = ErrorLog::from($givenValue)->toString();

        // Then
        $expectedString = 'Submission dropped for form 42: email: is required';
        self::assertSame($expectedString, $actualString);
    }

    #[DataProvider('blankValueProvider')]
    public function testThatItRefusesABlankMessage(string $givenValue): void
    {
        // Given

        // Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Error log must not be blank.');

        // When
        ErrorLog::from($givenValue);
    }

    public static function blankValueProvider(): \Generator
    {
        yield 'empty string' => [''];
        yield 'spaces only' => ['   '];
        yield 'tab and newline only' => ["\t\n"];
    }
}
