<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Domain;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ProcessPresenter;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ResetState;
use ChooseMyCompany\Shared\Domain\Response\ProcessResponse;

final class ProcessDomainPresenter implements ProcessPresenter, ProcessProvider, PresenterState, ResetState
{
    private Process $resource;

    private bool $presented = false;

    /**
     * @throws \LogicException
     */
    public function present(ProcessResponse $response): void
    {
        if ($this->presented) {
            throw new \LogicException('Process already presented');
        }

        $this->resource = $response->process;
        $this->presented = true;
    }

    public function provide(): Process
    {
        return $this->resource;
    }

    public function hasBeenPresented(): bool
    {
        return $this->presented;
    }

    public function reset(): void
    {
        unset($this->resource);
        $this->presented = false;
    }
}
