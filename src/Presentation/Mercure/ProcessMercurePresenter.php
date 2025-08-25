<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Mercure;

use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\ProcessMercureViewModel;

final class ProcessMercurePresenter implements ViewModelAccess
{
    public function __construct(
        private readonly ProcessProvider $processProvider,
    ) {
    }

    public function viewModel(): ProcessMercureViewModel
    {
        $process = $this->processProvider->provide();

        return new ProcessMercureViewModel(
            topics: $process->identifier->toString(),
            status: $process->state()->toString(),
        );
    }
}
