<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Presentation\Json\ViewModel\RetrieveJsonViewModel;

abstract class RetrieveJsonViewModelPresenter extends ErrorJsonViewModelPresenter
{
    protected function initializeViewModel(mixed $item): void
    {
        $this->viewModel = new RetrieveJsonViewModel(item: $item);
    }
}
