<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Presentation\Mapper\PaginationViewModelMapper;
use ChooseMyCompany\Shared\Presentation\Shared\CollectionResourceViewModelPresenter;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\CollectionJsonViewModel;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\PaginatedCollectionJsonViewModel;

/**
 * @template TResponse
 * @template TResources
 * @template TViewModel of CollectionJsonViewModel
 * @extends CollectionResourceViewModelPresenter<TResponse, TResources, TViewModel>
 */
abstract class CollectionJsonViewModelPresenter extends CollectionResourceViewModelPresenter
{
    protected function initializeViewModel(array $items): CollectionJsonViewModel
    {
        if (null === $this->pagination) {
            return new CollectionJsonViewModel(
                data: $items,
            );
        }

        return new PaginatedCollectionJsonViewModel(
            data: $items,
            pagination: PaginationViewModelMapper::domainToViewModel($this->pagination),
        );
    }
}
