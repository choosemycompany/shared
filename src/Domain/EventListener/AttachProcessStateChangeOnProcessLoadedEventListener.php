<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\EventListener;

use ChooseMyCompany\Shared\Domain\Event\ProcessLoadedEvent;
use ChooseMyCompany\Shared\Domain\Service\ProcessStateChangeAttacher;

final class AttachProcessStateChangeOnProcessLoadedEventListener
{
    public function __construct(
        private readonly ProcessStateChangeAttacher $attacher,
    ) {
    }

    public function __invoke(ProcessLoadedEvent $event): void
    {
        $this->attacher->attach($event->process);
    }
}
