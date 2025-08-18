<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Notification;

use ChooseMyCompany\Shared\Domain\Process\Process;

final class ProcessNotification
{
    public function __construct(
        public readonly Process $process,
    ) {
    }
}
