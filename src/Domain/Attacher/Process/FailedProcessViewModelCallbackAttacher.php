<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher\Process;

use ChooseMyCompany\Shared\Domain\Process\Process;

final class FailedProcessViewModelCallbackAttacher extends ProcessViewModelCallbackAttacher
{
    public function attach(): void
    {
        $viewModelAccess = $this->viewModelAccess;

        $this->process()->onStateChanged(
            static function (Process $process) use ($viewModelAccess): void {
                if ($process->state()->isFailed()) {
                    $process->withViewModel($viewModelAccess->viewModel());
                }
            }
        );
    }
}
