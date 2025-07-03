<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Domain;

use ChooseMyCompany\Shared\Domain\List\ErrorList;
use ChooseMyCompany\Shared\Domain\Response\ErrorListResponse;
use ChooseMyCompany\Shared\Domain\Service\ErrorListPresenter;
use ChooseMyCompany\Shared\Domain\Service\ErrorListProvider;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ResetState;

final class ErrorListDomainPresenter implements ErrorListPresenter, PresenterState, ErrorListProvider, ResetState
{
    private ErrorList $errors;

    private bool $presented = false;

    /**
     * @throws \LogicException
     */
    public function present(ErrorListResponse $response): void
    {
        if ($this->hasBeenPresented()) {
            throw new \LogicException('Error list has already been presented. You cannot call present() more than once.');
        }

        $this->errors = $response->errors;
        $this->presented = true;
    }

    public function hasBeenPresented(): bool
    {
        return $this->presented;
    }

    /**
     * @throws \LogicException
     */
    public function provide(): ErrorList
    {
        if ($this->hasBeenPresented()) {
            return $this->errors;
        }

        throw new \LogicException('No response has been presented. Call present() before provide().');
    }

    public function reset(): void
    {
        $this->presented = false;
        unset($this->errors);
    }
}
