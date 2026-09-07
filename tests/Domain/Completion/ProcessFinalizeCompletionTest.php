<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Domain\Completion;

use ChooseMyCompany\Shared\Domain\Completion\ProcessFinalizeCompletion;
use ChooseMyCompany\Shared\Tests\Support\Builder\ProcessBuilder;
use ChooseMyCompany\Shared\Tests\Support\Faker\FakePresenterState;
use ChooseMyCompany\Shared\Tests\Support\Faker\FakeProcessProvider;
use PHPUnit\Framework\TestCase;

final class ProcessFinalizeCompletionTest extends TestCase
{
    public function testThatTheProcessIsCompletedWithoutAnyFailureOutcome(): void
    {
        // Given
        $givenProcess = (new ProcessBuilder())->build();
        $sut = new ProcessFinalizeCompletion(
            new FakeProcessProvider($givenProcess),
        );

        // When
        $sut->complete();

        // Then
        self::assertTrue($givenProcess->state()->isCompleted());
    }

    public function testThatTheProcessIsCompletedWhenNoFailureHasBeenPresented(): void
    {
        // Given
        $givenProcess = (new ProcessBuilder())->build();
        $sut = new ProcessFinalizeCompletion(
            new FakeProcessProvider($givenProcess),
            new FakePresenterState(presented: false),
            new FakePresenterState(presented: false),
        );

        // When
        $sut->complete();

        // Then
        self::assertTrue($givenProcess->state()->isCompleted());
    }

    public function testThatTheProcessFailsWhenTheFirstFailureOutcomeHasBeenPresented(): void
    {
        // Given
        $givenProcess = (new ProcessBuilder())->build();
        $sut = new ProcessFinalizeCompletion(
            new FakeProcessProvider($givenProcess),
            new FakePresenterState(presented: true),
            new FakePresenterState(presented: false),
        );

        // When
        $sut->complete();

        // Then
        self::assertTrue($givenProcess->hasStateFailed());
    }

    public function testThatTheProcessFailsWhenAnyLaterFailureOutcomeHasBeenPresented(): void
    {
        // Given
        $givenProcess = (new ProcessBuilder())->build();
        $sut = new ProcessFinalizeCompletion(
            new FakeProcessProvider($givenProcess),
            new FakePresenterState(presented: false),
            new FakePresenterState(presented: true),
        );

        // When
        $sut->complete();

        // Then
        self::assertTrue($givenProcess->hasStateFailed());
    }
}
