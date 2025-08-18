<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\Notification\ProcessNotification;

interface ProcessNotificationPublishing
{
    public function publish(ProcessNotification $notification): void;
}
