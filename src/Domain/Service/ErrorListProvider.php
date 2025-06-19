<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\List\ErrorList;

interface ErrorListProvider
{
    public function provide(): ErrorList;
}
