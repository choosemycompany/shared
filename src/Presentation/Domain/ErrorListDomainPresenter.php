<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Domain;

use ChooseMyCompany\Shared\Domain\List\ErrorList;
use ChooseMyCompany\Shared\Domain\Response\ErrorListResponse;
use ChooseMyCompany\Shared\Domain\Service\ErrorListPresenter;
use ChooseMyCompany\Shared\Domain\Service\ErrorListProvider;
use ChooseMyCompany\Shared\Domain\Service\ErrorListViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\ValueObject\Error;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\ErrorListJsonViewModel;
use ChooseMyCompany\Shared\Presentation\ViewModel\Shared\ErrorViewModel;

final class ErrorListDomainPresenter implements ErrorListPresenter, PresenterState, ErrorListProvider
{
    private ErrorList $errors;

    public function present(ErrorListResponse $response): void
    {
        $this->errors = $response->errors;
    }

    public function hasBeenPresented(): bool
    {
        return isset($this->errors);
    }

    public function provide(): ErrorList
    {
        return $this->errors;
    }
}
