<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Shared\Pagination;

use ChooseMyCompany\Shared\Domain\ValueObject\Pagination\PaginationDetails;

trait PaginationViewModelBuilderTrait
{
    private function buildPaginationViewModel(PaginationDetails $paginationDetails): PaginationViewModel
    {
        return new PaginationViewModel(
            totalItems: $paginationDetails->totalItems,
            current: $paginationDetails->current,
            limit: $paginationDetails->limit
        );
    }
}
