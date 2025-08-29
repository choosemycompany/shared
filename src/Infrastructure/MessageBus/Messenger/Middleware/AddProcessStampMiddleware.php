<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Middleware;

use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Infrastructure\MessageBus\Messenger\Stamp\ProcessStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

final class AddProcessStampMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ProcessProvider&PresenterState $processOutcome,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null !== $envelope->last(ReceivedStamp::class)) {
            return $stack->next()->handle($envelope, $stack);
        }

        if ($this->processOutcome->hasBeenPresented()) {
            $process = $this->processOutcome->provide();
            $envelope = $envelope->with(new ProcessStamp($process));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
