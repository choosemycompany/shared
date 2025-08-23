<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Mercure;

use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Domain\Service\ViewModelAccess;
use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\ItemProcessMercureViewModel;

/**
 * @template TResource
 * @template TResourceViewModel
 */
abstract class ItemMercurePresenter implements ViewModelAccess
{
    /** @var TResource */
    protected mixed $resource;

    public function __construct(
        private readonly ProcessProvider $processProvider,
    ) {
    }

    /**
     * @throws \LogicException
     */
    public function viewModel(): ItemProcessMercureViewModel
    {
        $process = $this->processProvider->provide();

        return new ItemProcessMercureViewModel(
            topics: $process->identifier->toString(),
            status: $process->state()->toString(),
            item: $this->createResourceViewModel(),
        );
    }

    /**
     * @return TResourceViewModel
     */
    abstract protected function createResourceViewModel(): mixed;
}
