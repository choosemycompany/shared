<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Domain\Response\NotFoundResponse;
use ChooseMyCompany\Shared\Domain\Service\NotFoundViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\NotFoundJsonViewModel;

final class NotFoundJsonViewModelPresenter implements NotFoundViewModelPresenter, PresenterState, ViewModelAccess
{
    private NotFoundJsonViewModel $viewModel;

    public function present(NotFoundResponse $response): void
    {
        $this->viewModel = new NotFoundJsonViewModel(
            resource: $response->resourceName,
            filter: $response->filter->toString(),
            message: \sprintf('%s not found with filter: %s.', $response->resourceName, $response->filter->toString()),
        );
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): NotFoundJsonViewModel
    {
        if (!isset($this->viewModel)) {
            throw new \LogicException('ViewModel has not been set. Call present() before viewModel().');
        }

        return $this->viewModel;
    }

    public function hasBeenPresented(): bool
    {
        return isset($this->viewModel);
    }
}
