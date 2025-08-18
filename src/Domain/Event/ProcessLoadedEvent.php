<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Event;

use ChooseMyCompany\Shared\Domain\Process\Process;

final class ProcessLoadedEvent
{
    public function __construct(
        public readonly Process $process,
    ) {
    }
}
