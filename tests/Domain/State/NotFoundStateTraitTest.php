<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\State;

use ChooseMyCompany\Shared\Tests\Support\Faker\FakeNotFoundState;
use PHPUnit\Framework\TestCase;

final class NotFoundStateTraitTest extends TestCase
{
    public function testThatItIsFoundByDefault(): void
    {
        // Given
        $sut = new FakeNotFoundState();

        // When
        $actualIsNotFound = $sut->isNotFound();

        // Then
        self::assertFalse($actualIsNotFound);
    }

    public function testThatItIsNotFoundOnceMarked(): void
    {
        // Given
        $sut = new FakeNotFoundState();

        // When
        $sut->markNotFound();

        // Then
        self::assertTrue($sut->isNotFound());
    }

    public function testThatMarkingNotFoundTwiceIsForbidden(): void
    {
        // Given
        $sut = new FakeNotFoundState();
        $sut->markNotFound();

        // Then
        $this->expectException(\LogicException::class);

        // When
        $sut->markNotFound();
    }

    public function testThatProceedingIsAllowedWhenFound(): void
    {
        // Given
        $sut = new FakeNotFoundState();

        // When
        $sut->proceed();

        // Then
        self::assertTrue($sut->hasProceeded());
    }

    public function testThatProceedingIsForbiddenWhenNotFound(): void
    {
        // Given
        $sut = new FakeNotFoundState();
        $sut->markNotFound();

        // Then
        $this->expectException(\LogicException::class);

        // When
        $sut->proceed();
    }
}
