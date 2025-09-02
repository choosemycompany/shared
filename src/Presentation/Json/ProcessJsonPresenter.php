<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\JsonViewModel;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\ProcessJsonViewModel;

final class ProcessJsonPresenter implements ViewModelAccess
{
    /**
     * @param PresenterState&ViewModelAccess<JsonViewModel> $errorsOutcome
     */
    public function __construct(
        protected readonly ViewModelAccess&PresenterState $errorsOutcome,
        protected readonly ProcessProvider&PresenterState $processOutcome,
    ) {
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): JsonViewModel
    {
        if ($this->errorsOutcome->hasBeenPresented()) {
            return $this->errorsOutcome->viewModel();
        }

        if ($this->processOutcome->hasBeenPresented()) {
            return $this->createViewModel();
        }

        throw new \LogicException('No response has been presented. Call present() before viewModel().');
    }

    private function createViewModel(): ProcessJsonViewModel
    {
        $process = $this->processOutcome->provide();

        return new ProcessJsonViewModel(
            identifier: $process->identifier->toString(),
            status: $process->state()->toString(),
        );
    }
}
