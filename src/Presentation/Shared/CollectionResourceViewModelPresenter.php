<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Shared;

use ChooseMyCompany\Shared\Domain\Service\AccessDeniedViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\ErrorListViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\NotFoundViewModelPresenter;
use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ResetState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Domain\ValueObject\Pagination\PaginationDetails;

/**
 * @template TResponse
 * @template TResources
 * @template TViewModel
 */
abstract class CollectionResourceViewModelPresenter implements PresenterState, ViewModelAccess, ResetState
{
    /** @var TResources */
    protected mixed $resources;

    protected ?PaginationDetails $pagination = null;

    private bool $presented = false;

    public function __construct(
        protected readonly ErrorListViewModelPresenter&PresenterState $errorsPresenter,
        protected readonly NotFoundViewModelPresenter&PresenterState $notFoundPresenter,
        protected readonly AccessDeniedViewModelPresenter&PresenterState $accessDeniedPresenter,
    ) {
    }

    /**
     * @param TResponse $response
     *
     * @throws \LogicException
     */
    final public function present(mixed $response): void
    {
        if ($this->errorsPresenter->hasBeenPresented()) {
            throw new \LogicException('Error list has already been presented.');
        }

        if ($this->hasBeenPresented()) {
            throw new \LogicException('The response has already been presented. You cannot call present() more than once.');
        }

        $this->resources = $this->extractResources($response);
        $this->pagination = $this->extractPagination($response);
        $this->presented = true;
    }

    public function hasBeenPresented(): bool
    {
        return $this->presented;
    }

    /**
     * @param TResponse $response
     * @return TResources
     */
    abstract protected function extractResources(mixed $response): mixed;

    /**
     * @param TResponse $response
     */
    protected function extractPagination(mixed $response): ?PaginationDetails
    {
        return $response->pagination ?? null;
    }

    /**
     * @return TViewModel
     *
     * @throws \LogicException
     */
    final public function viewModel(): mixed
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
     * @return TViewModel
     */
    abstract protected function createViewModel(): mixed;

    public function reset(): void
    {
        $this->presented = false;
        unset($this->resources);
        unset($this->pagination);
    }
}
