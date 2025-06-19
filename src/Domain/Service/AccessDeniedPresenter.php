<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\Response\AccessDeniedResponse;

interface AccessDeniedPresenter
{
    public function present(AccessDeniedResponse $response): void;
}
