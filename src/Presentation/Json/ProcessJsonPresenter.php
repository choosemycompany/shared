<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Response\ProcessResponse;
use ChooseMyCompany\Shared\Domain\Service\ProcessPresenter;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Presentation\Shared\ResourceViewModelPresenter;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\ProcessJsonViewModel;

/**
 * @extends ResourceViewModelPresenter<ProcessResponse, Process, ProcessJsonViewModel>
 */
final class ProcessJsonPresenter extends ResourceViewModelPresenter implements ProcessPresenter, ProcessProvider
{
    protected function extract(mixed $response): Process
    {
        return $response->process;
    }

    protected function createViewModel(): ProcessJsonViewModel
    {
        return new ProcessJsonViewModel(
            identifier: $this->resource->identifier->toString(),
            status: $this->resource->state()->toString(),
        );
    }

    public function provide(): Process
    {
        return $this->resource;
    }
}
