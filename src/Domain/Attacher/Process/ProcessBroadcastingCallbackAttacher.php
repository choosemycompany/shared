<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher\Process;

use ChooseMyCompany\Shared\Domain\Service\Attacher;
use ChooseMyCompany\Shared\Domain\Service\Broadcasting;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;

final class ProcessBroadcastingCallbackAttacher implements Attacher
{
    public function __construct(
        private readonly ProcessProvider $processProvider,
        private readonly Broadcasting $broadcasting,
    ) {
    }

    public function attach(): void
    {
        $process = $this->processProvider->provide();
        $broadcasting = $this->broadcasting;

        $process->onStateChanged(
            static function () use ($broadcasting): void {
                $broadcasting->broadcast();
            }
        );
    }
}
