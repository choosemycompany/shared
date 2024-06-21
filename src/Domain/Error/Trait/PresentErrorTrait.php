<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error\Trait;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;
use ChooseMyCompany\Shared\Domain\Error\ErrorPresenter;
use ChooseMyCompany\Shared\Domain\Error\ErrorResponse;

trait PresentErrorTrait
{
    private function presentError(ErrorPresenter $errorPresenter, ErrorList $errors): void
    {
        $errorPresenter->presentError(new ErrorResponse($errors));
    }
}
