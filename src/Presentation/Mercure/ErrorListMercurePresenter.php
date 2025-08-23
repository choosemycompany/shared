<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Mercure;

use ChooseMyCompany\Shared\Domain\Service\ErrorListProvider;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Domain\ValueObject\Error;
use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\ErrorListProcessMercureViewModel;
use ChooseMyCompany\Shared\Presentation\ViewModel\Shared\ErrorViewModel;

final class ErrorListMercurePresenter implements ViewModelAccess
{
    public function __construct(
        private readonly ErrorListProvider $errorsProvider,
        private readonly ProcessProvider $processProvider,
    ) {
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): ErrorListProcessMercureViewModel
    {
        $errors = \array_map(
            static fn (Error $error) => new ErrorViewModel(
                message: $error->message,
                field: $error->field ?? '',
            ),
            $this->errorsProvider->provide()->all(),
        );

        $process = $this->processProvider->provide();

        return new ErrorListProcessMercureViewModel(
            topics: $process->identifier->toString(),
            status: $process->state()->toString(),
            errors: $errors,
        );
    }
}
