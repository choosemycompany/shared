<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Result;

use ChooseMyCompany\Shared\Domain\List\ErrorList;

interface FailureResult
{
    public function hasFailed(): bool;

    public function errors(): ErrorList;
}
