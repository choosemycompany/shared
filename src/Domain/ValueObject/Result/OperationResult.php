<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject\Result;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;
use ChooseMyCompany\Shared\Presentation\Domain\ViewModel\DomainViewModel;

final class OperationResult
{
    private function __construct(private readonly DomainViewModel $viewModel)
    {
    }

    public static function create(DomainViewModel $viewModel): self
    {
        return new self($viewModel);
    }

    public function isSuccess(): bool
    {
        return !$this->viewModel->isErrorViewModel();
    }

    public function isFailure(): bool
    {
        return $this->viewModel->isErrorViewModel();
    }

    public function getViewModel(): DomainViewModel
    {
        return $this->viewModel;
    }

    public function getErrors(): ErrorList
    {
        if ($this->isSuccess()) {
            return new ErrorList();
        }

        return $this->viewModel->errors;
    }
}
