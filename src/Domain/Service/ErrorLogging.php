<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

interface ErrorLogging
{
    public function log(string $errorMessage): void;
}
