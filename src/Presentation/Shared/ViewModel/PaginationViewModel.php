<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Shared\ViewModel;

final class PaginationViewModel
{
    public readonly int $total;

    public readonly int $previous;

    public readonly int $next;

    public function __construct(
        public readonly int $totalItems,
        public readonly int $current,
        public readonly int $limit
    ) {
        $this->total = $this->calculateTotal($totalItems, $limit);
        $this->previous = $this->calculatePrevious();
        $this->next = $this->calculateNext();
    }

    private function calculateTotal(int $totalItems, int $itemsPerPage): int
    {
        return (int) \ceil($totalItems / $itemsPerPage);
    }

    private function calculatePrevious(): int
    {
        return $this->current > 1 ? $this->current - 1 : $this->current;
    }

    private function calculateNext(): int
    {
        return $this->current < $this->total ? $this->current + 1 : $this->current;
    }
}
