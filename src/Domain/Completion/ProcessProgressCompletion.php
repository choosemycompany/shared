<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Completion;

use ChooseMyCompany\Shared\Domain\Service\Completion;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;

final class ProcessProgressCompletion implements Completion
{
    public function __construct(
        private readonly ProcessProvider $processProvider,
    ) {
    }

    public function complete(): void
    {
        $process = $this->processProvider->provide();

        if ($process->hasStateFailed()) {
            return;
        }

        $process->inProgress();
    }
}
