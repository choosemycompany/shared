<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Factory;

use ChooseMyCompany\Shared\Domain\Message\Message;
use ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Service\EnvelopeCreation;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;

final class EnvelopeFactory implements EnvelopeCreation
{
    public function create(Message $message): Envelope
    {
        return new Envelope(
            $message,
            [new AmqpStamp($message->getRoutingKey())]
        );
    }
}
