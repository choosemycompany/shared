<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Initiation;

use ChooseMyCompany\Shared\Domain\Service\Initiation;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;

final class ProcessStartInitiation implements Initiation
{
    public function __construct(
        private readonly ProcessProvider $processProvider,
    ) {
    }

    public function initiation(): void
    {
        $process = $this->processProvider->provide();

        $process->started();
    }
}
