<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\ValueObject\ErrorLog;

interface ErrorLogging
{
    public function log(ErrorLog $errorLog): void;
}
