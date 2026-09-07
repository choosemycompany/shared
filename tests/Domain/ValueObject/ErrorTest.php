<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\ValueObject;

use ChooseMyCompany\Shared\Domain\ValueObject\Error;
use PHPUnit\Framework\TestCase;

final class ErrorTest extends TestCase
{
    public function testThatItRendersTheFieldAndTheMessage(): void
    {
        // Given
        $sut = new Error('is required', 'email');

        // When
        $actualString = $sut->toString();

        // Then
        self::assertSame('email: is required', $actualString);
    }

    public function testThatItRendersTheMessageAloneWithoutField(): void
    {
        // Given
        $sut = new Error('something went wrong');

        // When
        $actualString = $sut->toString();

        // Then
        self::assertSame('something went wrong', $actualString);
    }
}
