<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Mercure;

use ChooseMyCompany\Shared\Domain\List\ErrorList;
use ChooseMyCompany\Shared\Domain\Response\ErrorListResponse;
use ChooseMyCompany\Shared\Domain\Service\ErrorListPresenter;
use ChooseMyCompany\Shared\Domain\Service\ErrorListProvider;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ResetState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Domain\ValueObject\Error;
use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\ErrorListProcessMercureViewModel;
use ChooseMyCompany\Shared\Presentation\ViewModel\Shared\ErrorViewModel;

final class ErrorListMercurePresenter implements
    ErrorListPresenter,
    PresenterState,
    ErrorListProvider,
    ViewModelAccess,
    ResetState
{
    private ErrorList $errors;

    private bool $presented = false;

    public function __construct(
        private readonly ProcessProvider $processProvider,
    ) {
    }

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

    /**
     * @throws \LogicException
     */
    public function viewModel(): ErrorListProcessMercureViewModel
    {
        if ($this->hasBeenPresented()) {
            return $this->createViewModel();
        }

        throw new \LogicException('No response has been presented. Call present() before viewModel().');
    }

    public function hasBeenPresented(): bool
    {
        return $this->presented;
    }

    private function createViewModel(): ErrorListProcessMercureViewModel
    {
        $errors = \array_map(
            static fn (Error $error) => new ErrorViewModel(
                message: $error->message,
                field: $error->field ?? '',
            ),
            $this->errors->all(),
        );

        $process = $this->processProvider->provide();

        return new ErrorListProcessMercureViewModel(
            topics: $process->identifier->toString(),
            status: $process->state()->toString(),
            errors: $errors,
        );
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
        unset($this->errors);
        $this->presented = false;
    }
}
