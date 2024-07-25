<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Domain\Error\ViewModelErrorPresenter;
use ChooseMyCompany\Shared\Presentation\Json\ViewModel\NoContentJsonViewModel;

final class NoContentJsonViewModelPresenter extends ErrorJsonViewModelPresenter implements ViewModelErrorPresenter
{
    public function __construct()
    {
        $this->viewModel = new NoContentJsonViewModel();
    }
}
