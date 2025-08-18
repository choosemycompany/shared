<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Initiation;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Process\ProcessIdentifier;
use ChooseMyCompany\Shared\Domain\Response\ProcessResponse;
use ChooseMyCompany\Shared\Domain\Service\IdentifierGeneration;
use ChooseMyCompany\Shared\Domain\Service\Initiation;
use ChooseMyCompany\Shared\Domain\Service\ProcessPresenter;

final class ProcessInitiation implements Initiation
{
    public function __construct(
        private readonly IdentifierGeneration $identifierGeneration,
        private readonly ProcessPresenter $processPresenter,
    ) {
    }

    public function initiation(): void
    {
        $process = Process::initiated(
            identifier: new ProcessIdentifier($this->identifierGeneration->generate()),
        );

        $response = new ProcessResponse($process);

        $this->processPresenter->present($response);
    }
}
