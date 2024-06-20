<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Presentation\Shared\Pagination\PaginationViewModel;

final class PaginatedCollectionJsonViewModel extends CollectionJsonViewModel
{
    public function __construct(array $data, public readonly PaginationViewModel $pagination)
    {
        parent::__construct($data);
    }
}
