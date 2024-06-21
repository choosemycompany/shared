<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Domain;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;
use ChooseMyCompany\Shared\Domain\Error\ErrorResponse;
use ChooseMyCompany\Shared\Domain\Error\ErrorStatefulPresenter;

final class ErrorStatefulDomainPresenter implements ErrorStatefulPresenter
{
    private bool $hasErrors = false;

    private ErrorList $errors;

    public function presentError(ErrorResponse $errorResponse): void
    {
        $this->hasErrors = true;
        $this->errors = $errorResponse->errors;
    }

    public function hasError(): bool
    {
        return $this->hasErrors;
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
