<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Domain\Response\AccessDeniedResponse;
use ChooseMyCompany\Shared\Domain\Service\AccessDeniedViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\AccessDeniedJsonViewModel;

final class AccessDeniedJsonViewModelPresenter implements AccessDeniedViewModelPresenter, PresenterState, ViewModelAccess
{
    private AccessDeniedJsonViewModel $viewModel;

    public function present(AccessDeniedResponse $response): void
    {
        $this->viewModel = new AccessDeniedJsonViewModel(
            resource: $response->resourceName,
            filter: $response->filter->toString(),
            message: \sprintf(
                'Access denied to resource %s using filter %s.',
                $response->resourceName,
                $response->filter->toString()
            ),
        );
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): AccessDeniedJsonViewModel
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
