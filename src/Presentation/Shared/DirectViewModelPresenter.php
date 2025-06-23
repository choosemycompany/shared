<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Shared;

use ChooseMyCompany\Shared\Domain\Service\PresenterState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;

/**
 * @template TResponse
 * @template TResource
 * @template TViewModel
 */
abstract class DirectViewModelPresenter implements PresenterState, ViewModelAccess
{
    /** @var TViewModel|null */
    private mixed $viewModel = null;

    private bool $presented = false;

    /**
     * @param TResponse $response
     *
     * @throws \LogicException
     */
    final public function present(mixed $response): void
    {
        if ($this->isPresented()) {
            throw new \LogicException('ViewModel has already been set. Call present() only once.');
        }

        $this->viewModel = $this->buildViewModel($response);
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
    final public function viewModel(): mixed
    {
        if ($this->isPresented()) {
            return $this->viewModel;
        }

        throw new \LogicException('ViewModel has not been set. Call present() before viewModel().');
    }

    final public function isPresented(): bool
    {
        return null !== $this->viewModel;
    }

    /**
     * @param  TResponse  $response
     * @return TViewModel
     */
    abstract protected function buildViewModel(mixed $response): mixed;
}
