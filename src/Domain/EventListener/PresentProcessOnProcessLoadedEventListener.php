<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\EventListener;

use ChooseMyCompany\Shared\Domain\Response\ProcessResponse;
use ChooseMyCompany\Shared\Domain\Event\ProcessLoadedEvent;
use ChooseMyCompany\Shared\Domain\Service\ProcessPresenter;

final class PresentProcessOnProcessLoadedEventListener
{
    public function __construct(
        private readonly ProcessPresenter $presenter,
    ) {
    }

    public function __invoke(ProcessLoadedEvent $event): void
    {
        $process = $event->process;
        $response = new ProcessResponse($process);
        $this->presenter->present($response);
    }
}
