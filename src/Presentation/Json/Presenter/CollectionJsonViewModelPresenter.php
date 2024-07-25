<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Domain\ValueObject\Pagination\PaginationDetails;
use ChooseMyCompany\Shared\Presentation\Json\ViewModel\CollectionJsonViewModel;
use ChooseMyCompany\Shared\Presentation\Shared\Pagination\PaginationViewModelBuilderTrait;

class CollectionJsonViewModelPresenter extends JsonViewModelPresenter
{
    protected function initializeViewModel(array $items, PaginationDetails $paginationDetails): void
    {
        $this->viewModel = new CollectionJsonViewModel(
            data: $items
        );
    }
}
