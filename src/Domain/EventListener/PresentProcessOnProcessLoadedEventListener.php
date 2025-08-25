<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\EventListener;

use ChooseMyCompany\Shared\Domain\Response\ProcessResponse;
use ChooseMyCompany\Shared\Domain\Event\ProcessLoadedEvent;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ProcessPresenter;

final class PresentProcessOnProcessLoadedEventListener
{
    public function __construct(
        private readonly ProcessPresenter&PresenterState $processPresenter,
    ) {
    }

    public function __invoke(ProcessLoadedEvent $event): void
    {
        if ($this->processPresenter->hasBeenPresented()) {
            return;
        }

        $process = $event->process;
        $response = new ProcessResponse($process);
        $this->processPresenter->present($response);
    }
}
