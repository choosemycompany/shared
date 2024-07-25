<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Presentation\Json\ViewModel\JsonViewModel;

abstract class JsonViewModelPresenter
{
    protected JsonViewModel $viewModel;

    public function viewModel(): JsonViewModel
    {
        return $this->viewModel;
    }
}
