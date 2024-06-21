<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Domain\Error\ErrorResponse;
use ChooseMyCompany\Shared\Domain\Error\ViewModelErrorPresenter;
use ChooseMyCompany\Shared\Presentation\Shared\Error\ErrorViewModelBuilderTrait;

final class NoContentJsonPresenter implements ViewModelErrorPresenter
{
    use ErrorViewModelBuilderTrait;

    private ErrorJsonViewModel|NoContentJsonViewModel $viewModel;

    public function __construct()
    {
        $this->viewModel = new NoContentJsonViewModel();
    }

    public function presentError(ErrorResponse $errorResponse): void
    {
        $this->viewModel = new ErrorJsonViewModel();
        $this->viewModel->errors = $this->buildErrorViewModels($errorResponse);
    }

    public function viewModel(): ErrorJsonViewModel|NoContentJsonViewModel
    {
        return $this->viewModel;
    }
}
