<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher\Process;

final class ProcessStateChangeBroadcastingAttacher extends ProcessBroadcastingAttacher
{
    public function attach(): void
    {
        $broadcasting = $this->broadcasting;
        $viewModelAccess = $this->viewModelAccess;

        $this->process()->onStateChanged(
            static function () use ($broadcasting, $viewModelAccess): void {
                $broadcasting->broadcast($viewModelAccess->viewModel());
            },
        );
    }
}
