<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\Presenter;

use ChooseMyCompany\Shared\Domain\Error\Error;
use ChooseMyCompany\Shared\Domain\Error\ErrorResponse;
use ChooseMyCompany\Shared\Presentation\Json\ViewModel\ErrorJsonViewModel;
use ChooseMyCompany\Shared\Presentation\Shared\ViewModel\ErrorViewModel;

abstract class ErrorJsonViewModelPresenter extends JsonViewModelPresenter
{
    public function presentError(ErrorResponse $errorResponse): void
    {
        $errors = \array_map(
            static fn (Error $error) => new ErrorViewModel($error->message, $error->fieldName),
            \iterator_to_array($errorResponse->errors, false)
        );

        $this->viewModel = new ErrorJsonViewModel($errors);
    }
}
