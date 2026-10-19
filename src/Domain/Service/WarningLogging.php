<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\ValueObject\WarningLog;

interface WarningLogging
{
    public function log(WarningLog $warningLog): void;
}
