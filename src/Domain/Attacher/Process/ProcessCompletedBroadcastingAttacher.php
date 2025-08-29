<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher\Process;

use ChooseMyCompany\Shared\Domain\Process\Process;

final class ProcessCompletedBroadcastingAttacher extends ProcessBroadcastingAttacher
{
    public function attach(): void
    {
        $broadcasting = $this->broadcasting;
        $viewModelAccess = $this->viewModelAccess;

        $this->process()->onStateChanged(
            static function (Process $process) use ($broadcasting, $viewModelAccess): void {
                if ($process->state()->isCompleted()) {
                    $broadcasting->broadcast($viewModelAccess->viewModel());
                }
            }
        );
    }
}
