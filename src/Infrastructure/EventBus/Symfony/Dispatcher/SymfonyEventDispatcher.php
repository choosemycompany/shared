<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\EventBus\Symfony\Dispatcher;

use ChooseMyCompany\Shared\Domain\Service\EventDispatching;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class SymfonyEventDispatcher implements EventDispatching
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(object $event, string $eventName = null): void
    {
        $this->eventDispatcher->dispatch($event, $eventName);
    }
}
