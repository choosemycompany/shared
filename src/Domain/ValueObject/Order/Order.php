<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject\Order;

abstract class Order
{
    /**
     * @param OrderBy[] $orderBys
     */
    public function __construct(
        public array $orderBys = [],
    ) {
    }

    public static function none(): static
    {
        return new static();
    }

    public function isEmpty(): bool
    {
        return empty($this->orderBys);
    }
}
