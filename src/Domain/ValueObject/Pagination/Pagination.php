<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject\Pagination;

use ChooseMyCompany\Shared\Extension\Assert\Assert;

final class Pagination
{
    public const PAGE = 1;
    public const ITEMS_PER_PAGE = 10;

    public readonly int $current;

    public readonly int $limit;

    public readonly int $offset;

    public function __construct(int $current, int $limit)
    {
        Assert::lazy()
            ->that($current, 'current')
            ->greaterThan(0)
            ->that($limit, 'limit')
            ->greaterThan(0)
            ->verifyNow()
        ;

        $this->current = $current;
        $this->limit = $limit;
        $this->offset = $this->calculateOffset();
    }

    private function calculateOffset(): int
    {
        return ($this->current - 1) * $this->limit;
    }
}
