<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Domain\Response\AccessDeniedResponse;
use ChooseMyCompany\Shared\Domain\Service\AccessDeniedViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ResetState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\AccessDeniedJsonViewModel;

final class AccessDeniedJsonViewModelPresenter implements AccessDeniedViewModelPresenter, PresenterState, ViewModelAccess, ResetState
{
    private AccessDeniedJsonViewModel $viewModel;

    private bool $presented = false;

    /**
     * @throws \LogicException
     */
    public function present(AccessDeniedResponse $response): void
    {
        if ($this->hasBeenPresented()) {
            throw new \LogicException('Response has already been presented. You cannot call present() more than once.');
        }

        $this->viewModel = new AccessDeniedJsonViewModel(
            resource: $response->resourceName,
            filter: $response->filter->toString(),
            message: \sprintf(
                'Access denied to resource %s using filter %s.',
                $response->resourceName,
                $response->filter->toString()
            ),
        );
        $this->presented = true;
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): AccessDeniedJsonViewModel
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
