<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Presentation\Json\ViewModel\CollectionJsonViewModel;

class CollectionJsonViewModelPresenter extends JsonViewModelPresenter
{
    protected function initializeViewModel(array $items): void
    {
        $this->viewModel = new CollectionJsonViewModel(
            data: $items
        );
    }
}
