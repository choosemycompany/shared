<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error;

interface ErrorStateChecker
{
    public function hasError(): bool;
}
