<?php

declare(strict_types=1);

namespace Tests\Extension\Assert;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;
use ChooseMyCompany\Shared\Extension\Assert\LazyAssertionException;
use PHPUnit\Framework\TestCase;

final class LazyAssertionExceptionTest extends TestCase
{
    public function testThatCreatesLazyExceptionFromErrorList(): void
    {
        // Given
        $givenErrorList = new ErrorList();
        $givenErrorList->addError('first message', 'first property path');
        $givenErrorList->addError('second message', 'second property path');

        // When
        $actualResult = LazyAssertionException::fromErrorList($givenErrorList);

        // Then
        self::assertInstanceOf(LazyAssertionException::class, $actualResult);
        self::assertCount(2, $actualResult->getErrorExceptions());
    }
}
