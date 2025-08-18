<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Service\ProcessStateChangeAttacher;

final class MultipleProcessStateChangeAttacher implements ProcessStateChangeAttacher
{
    /**
     * @var ProcessStateChangeAttacher[]
     */
    private array $attachers;

    public function __construct(
        ProcessStateChangeAttacher ...$attachers,
    ) {
        $this->attachers = $attachers;
    }

    public function attach(Process $process): void
    {
        foreach ($this->attachers as $attacher) {
            $attacher->attach($process);
        }
    }
}
