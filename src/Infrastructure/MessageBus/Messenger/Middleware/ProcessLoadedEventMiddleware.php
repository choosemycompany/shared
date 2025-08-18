<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Middleware;

use ChooseMyCompany\Shared\Domain\Service\EventDispatching;
use ChooseMyCompany\Shared\Domain\Event\ProcessLoadedEvent;
use ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Stamp\ProcessStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

final class ProcessLoadedEventMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly EventDispatching $eventDispatching,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(ReceivedStamp::class)) {
            return $stack->next()->handle($envelope, $stack);
        }

        $stamp = $envelope->last(ProcessStamp::class);
        if (null !== $stamp) {
            $process = $stamp->process;
            $event = new ProcessLoadedEvent(
                $process,
            );
            $this->eventDispatching->dispatch($event);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
