<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher\Process;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Service\Attacher;
use ChooseMyCompany\Shared\Domain\Service\Broadcasting;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast\BroadcastViewModel;

abstract class ProcessBroadcastingAttacher implements Attacher
{
    /**
     * @param ViewModelAccess<BroadcastViewModel> $viewModelAccess
     */
    public function __construct(
        private readonly ProcessProvider $processProvider,
        protected readonly ViewModelAccess $viewModelAccess,
        protected readonly Broadcasting $broadcasting,
    ) {
    }

    protected function process(): Process
    {
        return $this->processProvider->provide();
    }

    public function attach(): void
    {
        $broadcasting = $this->broadcasting;
        $viewModelAccess = $this->viewModelAccess;

        $this->process()->onStateChanged(
            static function () use ($broadcasting, $viewModelAccess): void {
                $broadcasting->broadcast($viewModelAccess->viewModel());
            }
        );
    }
}
