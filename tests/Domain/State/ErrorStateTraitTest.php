<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\State;

use ChooseMyCompany\Shared\Domain\List\ErrorList;
use ChooseMyCompany\Shared\Domain\ValueObject\Error;
use ChooseMyCompany\Shared\Tests\Support\Faker\FakeErrorState;
use PHPUnit\Framework\TestCase;

final class ErrorStateTraitTest extends TestCase
{
    public function testThatItHasNoErrorsByDefault(): void
    {
        // Given
        $sut = new FakeErrorState();

        // When
        $actualHasErrors = $sut->hasErrors();
        $actualHasNoErrors = $sut->hasNoErrors();

        // Then
        self::assertFalse($actualHasErrors);
        self::assertTrue($actualHasNoErrors);
    }

    public function testThatItExposesTheErrorsOnceSet(): void
    {
        // Given
        $sut = new FakeErrorState();
        $givenErrors = new ErrorList(new Error('is required', 'email'));

        // When
        $sut->setErrors($givenErrors);

        // Then
        self::assertTrue($sut->hasErrors());
        self::assertFalse($sut->hasNoErrors());
        self::assertSame('email: is required', $sut->errors()->toString());
    }

    public function testThatSettingErrorsTwiceIsForbidden(): void
    {
        // Given
        $sut = new FakeErrorState();
        $sut->setErrors(new ErrorList(new Error('first')));
        $givenErrors = new ErrorList(new Error('second'));

        // Then
        $this->expectException(\LogicException::class);

        // When
        $sut->setErrors($givenErrors);
    }

    public function testThatReadingErrorsBeforeTheyAreSetIsForbidden(): void
    {
        // Given
        $sut = new FakeErrorState();

        // Then
        $this->expectException(\LogicException::class);

        // When
        $sut->errors();
    }

    public function testThatMutationIsAllowedWithoutErrors(): void
    {
        // Given
        $sut = new FakeErrorState();

        // When
        $sut->mutate();

        // Then
        self::assertTrue($sut->isMutated());
    }

    public function testThatMutationIsForbiddenWhenErrorsArePresent(): void
    {
        // Given
        $sut = new FakeErrorState();
        $sut->setErrors(new ErrorList(new Error('is required', 'email')));

        // Then
        $this->expectException(\LogicException::class);

        // When
        $sut->mutate();
    }
}
