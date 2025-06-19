<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject\Order;

use Assert\Assertion;
use ChooseMyCompany\Shared\Domain\Enum\SortDirection;

abstract class OrderBy
{
    final public function __construct(
        public readonly string $field,
        public readonly SortDirection $direction = SortDirection::ASC
    ) {
        Assertion::inArray($this->field, static::allowedFields(), \sprintf('Field "%s" is not allowed for ordering.', $this->field));
    }

    /**
     * @return string[]
     */
    abstract protected static function allowedFields(): array;
}
