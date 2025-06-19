<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Json;

use ChooseMyCompany\Shared\Presentation\ViewModel\Shared\PaginationViewModel;

final class PaginatedCollectionJsonViewModel extends CollectionJsonViewModel
{
    public function __construct(array $data, public readonly PaginationViewModel $pagination)
    {
        parent::__construct($data);
    }
}
