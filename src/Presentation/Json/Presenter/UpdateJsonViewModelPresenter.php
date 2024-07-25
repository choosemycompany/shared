<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Presentation\Json\ViewModel\UpdateJsonViewModel;

abstract class UpdateJsonViewModelPresenter extends ErrorJsonViewModelPresenter
{
    protected function initializeViewModel(mixed $item): void
    {
        $this->viewModel = new UpdateJsonViewModel(item: $item);
    }
}
