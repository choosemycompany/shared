<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Service\ProcessStateChangeAttacher;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;

final class InProgressProcessViewModelCallbackAttacher implements ProcessStateChangeAttacher
{
    public function __construct(
        private readonly ViewModelAccess $viewModelAccess,
    ) {
    }

    public function attach(Process $process): void
    {
        $process->onStateChanged(function (Process $process): void {
            if ($process->state()->isInProgress()) {
                $process->withViewModel($this->viewModelAccess->viewModel());
            }
        });
    }
}
