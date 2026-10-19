<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\ValueObject;

use ChooseMyCompany\Shared\Domain\ValueObject\InfoLog;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class InfoLogTest extends TestCase
{
    public function testThatItRendersTheGivenMessage(): void
    {
        // Given
        $givenMessage = 'Certificate update skipped for certificate "42": already certified';

        // When
        $actualString = InfoLog::from($givenMessage)->toString();

        // Then
        $expectedString = 'Certificate update skipped for certificate "42": already certified';
        self::assertSame($expectedString, $actualString);
    }

    #[DataProvider('blankMessageProvider')]
    public function testThatItRefusesABlankMessage(string $givenMessage): void
    {
        // Given

        // Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Info log must not be blank.');

        // When
        InfoLog::from($givenMessage);
    }

    public static function blankMessageProvider(): \Generator
    {
        yield 'empty string' => [''];
        yield 'spaces only' => ['   '];
        yield 'tab and newline only' => ["\t\n"];
    }
}
