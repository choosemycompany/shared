<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\Response\ErrorListResponse;

interface ErrorListPresenter
{
    public function present(ErrorListResponse $response): void;
}
