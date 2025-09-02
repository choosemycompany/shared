<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\Process\Process;

interface ProcessProvider
{
    public function provide(): Process;
}
