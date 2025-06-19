<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Mapper;

use ChooseMyCompany\Shared\Domain\ValueObject\Pagination\PaginationDetails;
use ChooseMyCompany\Shared\Presentation\ViewModel\Shared\PaginationViewModel;

final class PaginationViewModelMapper
{
    public static function domainToViewModel(PaginationDetails $pagination): PaginationViewModel
    {
        return new PaginationViewModel(
            totalItems: $pagination->totalItems,
            current: $pagination->current,
            limit: $pagination->limit
        );
    }
}
