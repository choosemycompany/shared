<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\Score\ValueObject;

use ChooseMyCompany\Shared\Tests\Support\Builder\FakeIdentifierBuilder;
use ChooseMyCompany\Shared\Tests\Support\Builder\FakeOtherIdentifierBuilder;
use PHPUnit\Framework\TestCase;

final class IdentifierTest extends TestCase
{
    public function testThatDifferentClassesAreNotEqual(): void
    {
        // Given
        $sut = (new FakeIdentifierBuilder())->build();

        $givenIdentifier = (new FakeOtherIdentifierBuilder())->build();

        // Then
        $this->expectException(\InvalidArgumentException::class);

        // When
        $sut->equals($givenIdentifier);
    }

    public function testThatSameClassWithDifferentValuesAreNotEqual(): void
    {
        // Given
        $sut = (new FakeIdentifierBuilder())->withIdentifier('91543519-5a2c-40a5-b2bb-b259d0f89406')->build();

        $givenIdentifier = (new FakeIdentifierBuilder())->withIdentifier('d5881a98-e827-4bc3-bbf4-9f21301cc2b2')->build();

        // When
        $actualResult = $sut->equals($givenIdentifier);

        // Then
        self::assertFalse($actualResult);
    }

    public function testThatSameClassWithSameValuesAreEqual(): void
    {
        // Given
        $identifierString = '02215a75-daa0-439c-91a3-641c2559f518';
        $sut = (new FakeIdentifierBuilder())->withIdentifier($identifierString)->build();

        $givenIdentifier = (new FakeIdentifierBuilder())->withIdentifier($identifierString)->build();

        // When
        $actualResult = $sut->equals($givenIdentifier);

        // Then
        self::assertTrue($actualResult);
    }
}
