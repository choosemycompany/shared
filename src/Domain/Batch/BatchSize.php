<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Batch;

use Assert\Assertion;

final class BatchSize
{
    public function __construct(
        public readonly int $value,
    ) {
        Assertion::greaterThan($this->value, 0, 'Batch size "%s" must be greater than "%s".');
    }

    public function isReachedBy(int $count): bool
    {
        return $count >= $this->value;
    }
}
