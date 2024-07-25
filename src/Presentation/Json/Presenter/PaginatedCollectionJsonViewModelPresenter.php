<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Domain\ValueObject\Pagination\PaginationDetails;
use ChooseMyCompany\Shared\Presentation\Json\ViewModel\PaginatedCollectionJsonViewModel;
use ChooseMyCompany\Shared\Presentation\Shared\ViewModel\PaginationViewModel;

class PaginatedCollectionJsonViewModelPresenter extends JsonViewModelPresenter
{
    protected function initializeViewModel(array $items, PaginationDetails $paginationDetails): void
    {
        $pagination  = new PaginationViewModel(
            totalItems: $paginationDetails->totalItems,
            current: $paginationDetails->current,
            limit: $paginationDetails->limit
        );

        $this->viewModel = new PaginatedCollectionJsonViewModel(
            data: $items,
            pagination: $pagination
        );
    }
}
