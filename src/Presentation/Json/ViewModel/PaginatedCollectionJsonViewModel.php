<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\ViewModel;

use ChooseMyCompany\Shared\Presentation\Shared\ViewModel\PaginationViewModel;

final class PaginatedCollectionJsonViewModel extends CollectionJsonViewModel
{
    public function __construct(array $data, public readonly PaginationViewModel $pagination)
    {
        parent::__construct($data);
    }
}
