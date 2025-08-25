<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Service;

use ChooseMyCompany\Shared\Domain\Message\Message;
use Symfony\Component\Messenger\Envelope;

interface EnvelopeCreation
{
    public function create(Message $message): Envelope;
}
