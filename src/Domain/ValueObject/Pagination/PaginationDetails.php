<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject\Pagination;

final class PaginationDetails
{
    public function __construct(
        public readonly int $totalItems,
        public readonly int $current,
        public readonly int $limit
    ) {
    }
}
