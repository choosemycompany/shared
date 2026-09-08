<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\State;

use ChooseMyCompany\Shared\Domain\List\ErrorList;

interface ErrorState
{
    public function hasErrors(): bool;

    public function hasNoErrors(): bool;

    public function errors(): ErrorList;
}
