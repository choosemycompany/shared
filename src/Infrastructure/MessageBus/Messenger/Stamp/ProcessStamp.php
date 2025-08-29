<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Stamp;

use ChooseMyCompany\Shared\Domain\Process\Process;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class ProcessStamp implements StampInterface
{
    public function __construct(
        public readonly Process $process,
    ) {
    }
}
