<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Response;

use ChooseMyCompany\Shared\Domain\Filter\Filter;

final class AccessDeniedResponse
{
    public function __construct(
        public readonly string $resourceName,
        public readonly Filter $filter,
    ) {
    }
}
