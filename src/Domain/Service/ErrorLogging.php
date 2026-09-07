<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\ValueObject\ErrorLogMessage;

interface ErrorLogging
{
    public function log(ErrorLogMessage $message): void;
}
