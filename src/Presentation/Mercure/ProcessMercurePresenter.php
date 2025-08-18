<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Mercure;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Response\ProcessResponse;
use ChooseMyCompany\Shared\Domain\Service\ProcessPresenter;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ResetState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\ProcessMercureViewModel;

final class ProcessMercurePresenter implements ProcessPresenter, ProcessProvider, ViewModelAccess, ResetState
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

    public function viewModel(): ProcessMercureViewModel
    {
        return new ProcessMercureViewModel(
            topics: $this->resource->identifier->toString(),
            status: $this->resource->state()->toString(),
        );
    }

    public function provide(): Process
    {
        return $this->resource;
    }

    public function reset(): void
    {
        unset($this->resource);
        $this->presented = false;
    }
}
