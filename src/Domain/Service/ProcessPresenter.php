<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\Response\ProcessResponse;

interface ProcessPresenter
{
    public function present(ProcessResponse $response): void;
}
