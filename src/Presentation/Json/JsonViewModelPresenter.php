<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Presentation\ViewModel\Json\JsonViewModel;

abstract class JsonViewModelPresenter
{
    protected JsonViewModel $viewModel;

    public function viewModel(): JsonViewModel
    {
        return $this->viewModel;
    }
}
