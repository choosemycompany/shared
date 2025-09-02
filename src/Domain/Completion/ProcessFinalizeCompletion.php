<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Completion;

use ChooseMyCompany\Shared\Domain\Service\Completion;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;

final class ProcessFinalizeCompletion implements Completion
{
    public function __construct(
        private readonly PresenterState $errorOutcome,
        private readonly ProcessProvider $processProvider,
    ) {
    }

    public function complete(): void
    {
        $process = $this->processProvider->provide();

        $hasBeenPresented = $this->errorOutcome->hasBeenPresented();
        if ($hasBeenPresented) {
            $process->failed();
        } else {
            $process->completed();
        }
    }
}
