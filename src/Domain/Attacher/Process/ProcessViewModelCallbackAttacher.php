<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher\Process;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Service\Attacher;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;

abstract class ProcessViewModelCallbackAttacher implements Attacher
{
    public function __construct(
        protected readonly ProcessProvider $processProvider,
        protected readonly ViewModelAccess $viewModelAccess,
    ) {
    }

    protected function process(): Process
    {
        return $this->processProvider->provide();
    }
}
