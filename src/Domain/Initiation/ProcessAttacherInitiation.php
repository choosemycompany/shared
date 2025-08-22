<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Initiation;

use ChooseMyCompany\Shared\Domain\Service\Initiation;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ProcessStateChangeAttacher;

final class ProcessAttacherInitiation implements Initiation
{
    public function __construct(
        private readonly ProcessProvider $provider,
        private readonly ProcessStateChangeAttacher $attacher,
    ) {
    }

    public function initiation(): void
    {
        $process = $this->provider->provide();

        $this->attacher->attach($process);
    }
}
