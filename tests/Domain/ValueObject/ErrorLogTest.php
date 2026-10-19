<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\ValueObject;

use ChooseMyCompany\Shared\Domain\ValueObject\ErrorLog;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ErrorLogTest extends TestCase
{
    private const EXCEPTION_MESSAGE = 'Connection refused';

    public function testThatItRendersTheGivenMessage(): void
    {
        // Given
        $givenMessage = 'Submission dropped for form 42: email: is required';

        // When
        $actualString = ErrorLog::from($givenMessage)->toString();

        // Then
        $expectedString = 'Submission dropped for form 42: email: is required';
        self::assertSame($expectedString, $actualString);
    }

    public function testThatItKeepsTheGivenException(): void
    {
        // Given
        $givenMessage = 'Invitation send to main failed for form 42';
        $givenException = new \RuntimeException(self::EXCEPTION_MESSAGE);

        // When
        $actualException = ErrorLog::from($givenMessage, $givenException)->exception();

        // Then
        self::assertInstanceOf(\RuntimeException::class, $actualException);
        self::assertSame(self::EXCEPTION_MESSAGE, $actualException->getMessage());
    }

    public function testThatItHasNoExceptionByDefault(): void
    {
        // Given
        $givenMessage = 'Question IDs 7 match no hf question';

        // When
        $actualException = ErrorLog::from($givenMessage)->exception();

        // Then
        self::assertNull($actualException);
    }

    #[DataProvider('blankMessageProvider')]
    public function testThatItRefusesABlankMessage(string $givenMessage): void
    {
        // Given

        // Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Error log must not be blank.');

        // When
        ErrorLog::from($givenMessage);
    }

    public static function blankMessageProvider(): \Generator
    {
        yield 'empty string' => [''];
        yield 'spaces only' => ['   '];
        yield 'tab and newline only' => ["\t\n"];
    }
}
