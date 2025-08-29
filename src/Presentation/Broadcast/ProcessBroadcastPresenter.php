<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Broadcast;

use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast\ProcessBroadcastViewModel;

final class ProcessBroadcastPresenter implements ViewModelAccess
{
    public function __construct(
        private readonly ProcessProvider $processProvider,
    ) {
    }

    public function viewModel(): ProcessBroadcastViewModel
    {
        $process = $this->processProvider->provide();

        return new ProcessBroadcastViewModel(
            topics: $process->identifier->toString(),
            status: $process->state()->toString(),
        );
    }
}
