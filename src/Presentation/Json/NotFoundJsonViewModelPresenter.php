<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Domain\Response\NotFoundResponse;
use ChooseMyCompany\Shared\Domain\Service\NotFoundViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ResetState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\NotFoundJsonViewModel;

final class NotFoundJsonViewModelPresenter implements NotFoundViewModelPresenter, PresenterState, ViewModelAccess, ResetState
{
    private NotFoundJsonViewModel $viewModel;

    private bool $presented = false;

    /**
     * @throws \LogicException
     */
    public function present(NotFoundResponse $response): void
    {
        if ($this->hasBeenPresented()) {
            throw new \LogicException('Response has already been presented. You cannot call present() more than once.');
        }

        $this->viewModel = new NotFoundJsonViewModel(
            resource: $response->resourceName,
            filter: $response->filter->toString(),
            message: \sprintf('%s not found with filter: %s.', $response->resourceName, $response->filter->toString()),
        );
        $this->presented = true;
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): NotFoundJsonViewModel
    {
        if ($this->hasBeenPresented()) {
            return $this->viewModel;
        }

        throw new \LogicException('ViewModel has not been set. Call present() before viewModel().');
    }

    public function hasBeenPresented(): bool
    {
        return $this->presented;
    }

    public function reset(): void
    {
        $this->presented = false;
        unset($this->viewModel);
    }
}
