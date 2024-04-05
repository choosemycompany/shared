<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error;

interface ErrorPresenter
{
    public function presentError(ErrorResponse $errorResponse): void;
}
