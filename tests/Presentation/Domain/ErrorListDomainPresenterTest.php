<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Presentation\Domain;

use ChooseMyCompany\Shared\Presentation\Domain\ErrorListDomainPresenter;
use ChooseMyCompany\Shared\Tests\Support\Builder\ErrorListResponseBuilder;
use PHPUnit\Framework\TestCase;

final class ErrorListDomainPresenterTest extends TestCase
{
    public function testThatResetWithSuccess(): void
    {
        // Given
        $sut = new ErrorListDomainPresenter();

        $givenResponse = (new ErrorListResponseBuilder())
            ->withErrorMessage('error message')
            ->build();
        $sut->present($givenResponse);

        // When
        $sut->reset();

        // Then
        self::assertFalse($sut->hasBeenPresented());

        // Given
        $givenResponse = (new ErrorListResponseBuilder())
            ->withErrorMessage('new error message')
            ->build();
        $sut->present($givenResponse);

        // Then
        $actualErrors = $sut->provide()->all();

        $firstError = \array_shift($actualErrors);
        self::assertSame('new error message', $firstError->message);
    }
}
