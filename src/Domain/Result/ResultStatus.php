<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Result;

interface ResultStatus
{
    public function hasSucceeded(): bool;
}
