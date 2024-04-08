<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error;

trait PresentError
{
    private function presentError(ErrorPresenter $errorPresenter, ErrorList $errors): void
    {
        $errorPresenter->presentError(new ErrorResponse($errors));
    }
}
