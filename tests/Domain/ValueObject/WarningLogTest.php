<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\ValueObject;

use ChooseMyCompany\Shared\Domain\ValueObject\WarningLog;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class WarningLogTest extends TestCase
{
    private const EXCEPTION_MESSAGE = 'Connection refused';

    public function testThatItRendersTheGivenMessage(): void
    {
        // Given
        $givenMessage = 'Verbatim tag associations skipped for 12 participants';

        // When
        $actualString = WarningLog::from($givenMessage)->toString();

        // Then
        $expectedString = 'Verbatim tag associations skipped for 12 participants';
        self::assertSame($expectedString, $actualString);
    }

    public function testThatItKeepsTheGivenException(): void
    {
        // Given
        $givenMessage = 'The verbatims of participant 7 could not be tagged';
        $givenException = new \RuntimeException(self::EXCEPTION_MESSAGE);

        // When
        $actualException = WarningLog::from($givenMessage, $givenException)->exception();

        // Then
        self::assertInstanceOf(\RuntimeException::class, $actualException);
        self::assertSame(self::EXCEPTION_MESSAGE, $actualException->getMessage());
    }

    public function testThatItHasNoExceptionByDefault(): void
    {
        // Given
        $givenMessage = 'Form 42 has no answer from open question to replicate';

        // When
        $actualException = WarningLog::from($givenMessage)->exception();

        // Then
        self::assertNull($actualException);
    }

    #[DataProvider('blankMessageProvider')]
    public function testThatItRefusesABlankMessage(string $givenMessage): void
    {
        // Given

        // Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Warning log must not be blank.');

        // When
        WarningLog::from($givenMessage);
    }

    public static function blankMessageProvider(): \Generator
    {
        yield 'empty string' => [''];
        yield 'spaces only' => ['   '];
        yield 'tab and newline only' => ["\t\n"];
    }
}
