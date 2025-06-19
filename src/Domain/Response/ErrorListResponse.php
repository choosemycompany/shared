<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Response;

use ChooseMyCompany\Shared\Domain\List\ErrorList;

final class ErrorListResponse
{
    public function __construct(
        public readonly ErrorList $errors,
    ) {
    }
}
