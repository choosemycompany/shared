<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Broadcast;

use ChooseMyCompany\Shared\Domain\Service\ErrorListProvider;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Domain\ValueObject\Error;
use ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast\ErrorListProcesBroadcastViewModel;
use ChooseMyCompany\Shared\Presentation\ViewModel\Shared\ErrorViewModel;

final class ErrorListProcessBroadcastPresenter implements ViewModelAccess
{
    public function __construct(
        private readonly ErrorListProvider $errorsProvider,
        private readonly ProcessProvider $processProvider,
    ) {
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): ErrorListProcesBroadcastViewModel
    {
        $errors = \array_map(
            static fn (Error $error) => new ErrorViewModel(
                message: $error->message,
                field: $error->field ?? '',
            ),
            $this->errorsProvider->provide()->all(),
        );

        $process = $this->processProvider->provide();

        return new ErrorListProcesBroadcastViewModel(
            topics: $process->identifier->toString(),
            status: $process->state()->toString(),
            errors: $errors,
        );
    }
}
