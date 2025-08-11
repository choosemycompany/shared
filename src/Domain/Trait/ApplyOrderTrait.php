<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Trait;

use ChooseMyCompany\Shared\Domain\ValueObject\Order\Order;
use Doctrine\ORM\QueryBuilder;

trait ApplyOrderTrait
{
    private function applyOrder(QueryBuilder $qb, string $alias, Order $order): void
    {
        if ($order->isEmpty()) {
            return;
        }

        foreach ($order->orderBys as $orderBy) {
            $qb->addOrderBy($alias . '.' . $orderBy->field, $orderBy->direction->value);
        }
    }
}
