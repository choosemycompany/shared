<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Completion;

use ChooseMyCompany\Shared\Domain\Service\Completion;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;

final class ProcessFinalizeCompletion implements Completion
{
    /**
     * @var PresenterState[]
     */
    private array $failureOutcomes;

    public function __construct(
        private readonly ProcessProvider $processProvider,
        PresenterState ...$failureOutcomes,
    ) {
        $this->failureOutcomes = $failureOutcomes;
    }

    public function complete(): void
    {
        $process = $this->processProvider->provide();

        if ($this->hasFailureBeenPresented()) {
            $process->failed();

            return;
        }

        $process->completed();
    }

    private function hasFailureBeenPresented(): bool
    {
        foreach ($this->failureOutcomes as $failureOutcome) {
            if ($failureOutcome->hasBeenPresented()) {
                return true;
            }
        }

        return false;
    }
}
