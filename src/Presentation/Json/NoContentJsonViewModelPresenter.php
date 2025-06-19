<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Domain\Service\AccessDeniedViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\ErrorListViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\NoContentViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\NotFoundViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\NoContentJsonViewModel;

final class NoContentJsonViewModelPresenter implements NoContentViewModelPresenter
{
    public function __construct(
        protected readonly ErrorListViewModelPresenter&PresenterState $errorsPresenter,
        protected readonly AccessDeniedViewModelPresenter&PresenterState $accessDeniedPresenter,
        protected readonly NotFoundViewModelPresenter&PresenterState $notFoundPresenter,
    ) {
    }

    public function viewModel(): mixed
    {
        if ($this->errorsPresenter->hasBeenPresented()) {
            return $this->errorsPresenter->viewModel();
        }

        if ($this->accessDeniedPresenter->hasBeenPresented()) {
            return $this->accessDeniedPresenter->viewModel();
        }

        if ($this->notFoundPresenter->hasBeenPresented()) {
            return $this->notFoundPresenter->viewModel();
        }

        return new NoContentJsonViewModel();
    }
}
