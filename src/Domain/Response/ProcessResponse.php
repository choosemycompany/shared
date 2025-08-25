<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Response;

use ChooseMyCompany\Shared\Domain\Process\Process;

final class ProcessResponse
{
    public function __construct(
        public Process $process,
    ) {
    }
}
