<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Presentation\Json\ViewModel\RegisterJsonViewModel;

abstract class RegisterJsonViewModelPresenter extends ErrorJsonViewModelPresenter
{
    protected function initializeViewModel(mixed $item): void
    {
        $this->viewModel = new RegisterJsonViewModel(item: $item);
    }
}
