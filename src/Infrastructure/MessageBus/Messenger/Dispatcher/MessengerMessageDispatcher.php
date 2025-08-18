<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Dispatcher;

use ChooseMyCompany\Shared\Domain\Service\MessageDispatching;
use ChooseMyCompany\Shared\Domain\Message\Message;
use ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Service\EnvelopeCreation;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerMessageDispatcher implements MessageDispatching
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly EnvelopeCreation $envelopeFactory,
    ) {
    }

    public function dispatch(Message $message): void
    {
        $this->bus->dispatch(
            $this->envelopeFactory->create($message)
        );
    }
}
