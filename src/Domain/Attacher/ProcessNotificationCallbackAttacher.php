<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher;

use ChooseMyCompany\Shared\Domain\Notification\ProcessNotification;
use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Service\ProcessNotificationPublishing;
use ChooseMyCompany\Shared\Domain\Service\ProcessStateChangeAttacher;

final class ProcessNotificationCallbackAttacher implements ProcessStateChangeAttacher
{
    public function __construct(
        private readonly ProcessNotificationPublishing $notificationPublisher,
    ) {
    }

    public function attach(Process $process): void
    {
        $process->onStateChanged(
            fn (Process $process) => $this->notificationPublisher->publish(new ProcessNotification($process))
        );
    }
}
