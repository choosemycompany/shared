<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Trait;

use ChooseMyCompany\Shared\Domain\Response\ErrorListResponse;
use ChooseMyCompany\Shared\Domain\Result\FailureResult;
use ChooseMyCompany\Shared\Domain\Service\ErrorListPresenter;

trait PresentErrorsTrait
{
    private function presentErrors(ErrorListPresenter $presenter, FailureResult $result): void
    {
        $presenter->present(new ErrorListResponse($result->errors()));
    }
}
