<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Shared;

use ChooseMyCompany\Shared\Domain\Service\AccessDeniedViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\ErrorListViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\NotFoundViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ResetState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;

/**
 * @template TResponse
 * @template TResource
 * @template TViewModel
 */
abstract class ResourceViewModelPresenter implements PresenterState, ViewModelAccess, ResetState
{
    /** @var TResource */
    protected mixed $resource;

    protected bool $presented = false;

    public function __construct(
        protected readonly ErrorListViewModelPresenter&PresenterState $errorsPresenter,
        protected readonly AccessDeniedViewModelPresenter&PresenterState $accessDeniedPresenter,
        protected readonly NotFoundViewModelPresenter&PresenterState $notFoundPresenter,
    ) {
    }

    /**
     * @param TResponse $response
     *
     * @throws \LogicException
     */
    public function present(mixed $response): void
    {
        if ($this->errorsPresenter->hasBeenPresented()) {
            throw new \LogicException('Error list has already been presented.');
        }

        if ($this->hasBeenPresented()) {
            throw new \LogicException('The response has already been presented. You cannot call present() more than once.');
        }

        $this->resource = $this->extract($response);
        $this->presented = true;
    }

    public function hasBeenPresented(): bool
    {
        return $this->presented;
    }

    /**
     * @return TViewModel
     *
     * @throws \LogicException
     */
    public function viewModel(): mixed
    {
        if ($this->errorsPresenter->hasBeenPresented()) {
            return $this->errorsPresenter->viewModel();
        }

        if ($this->accessDeniedPresenter->hasBeenPresented()) {
            return $this->accessDeniedPresenter->viewModel();
        }

        if ($this->notFoundPresenter->hasBeenPresented()) {
            return $this->notFoundPresenter->viewModel();
        }

        if ($this->hasBeenPresented()) {
            return $this->createViewModel();
        }

        throw new \LogicException('No response has been presented. Call present() before viewModel().');
    }

    /**
     * @param  TResponse $response
     * @return TResource
     */
    abstract protected function extract(mixed $response): mixed;

    /**
     * @return TViewModel
     */
    abstract protected function createViewModel(): mixed;

    public function reset(): void
    {
        $this->presented = false;
        unset($this->resource);
    }
}
