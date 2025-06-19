<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\Response\NotFoundResponse;

interface NotFoundPresenter
{
    public function present(NotFoundResponse $response): void;
}
