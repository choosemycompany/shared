<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Mercure;

use ChooseMyCompany\Shared\Domain\Service\ResetState;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\ItemProcessMercureViewModel;

/**
 * @template TResponse
 * @template TResource
 * @template TResourceViewModel
 */
abstract class ItemMercurePresenter implements ViewModelAccess, ResetState
{
    /** @var TResource */
    protected mixed $resource;

    protected bool $presented = false;

    public function __construct(
        private readonly ProcessMercurePresenter $processPresenter,
    ) {
    }

    /**
     * @param TResponse $response
     *
     * @throws \LogicException
     */
    public function present(mixed $response): void
    {
        if ($this->presented) {
            throw new \LogicException('The response has already been presented. You cannot call present() more than once.');
        }

        $this->resource = $this->extract($response);
        $this->presented = true;
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): ItemProcessMercureViewModel
    {
        if ($this->presented) {
            return $this->createMercureViewModel();
        }

        throw new \LogicException('No response has been presented. Call present() before viewModel().');
    }

    /**
     * @param TResponse $response
     *
     * @return TResource
     */
    abstract protected function extract(mixed $response): mixed;

    /**
     * @return TResourceViewModel
     */
    abstract protected function createResourceViewModel(): mixed;

    private function createMercureViewModel(): ItemProcessMercureViewModel
    {
        $process = $this->processPresenter->provide();

        return new ItemProcessMercureViewModel(
            topics: $process->identifier->toString(),
            status: $process->state()->toString(),
            item: $this->createResourceViewModel(),
        );
    }

    public function reset(): void
    {
        unset($this->resource);
        $this->presented = false;
    }
}
