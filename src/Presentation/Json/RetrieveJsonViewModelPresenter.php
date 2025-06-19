<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Presentation\Shared\ResourceViewModelPresenter;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\RetrieveJsonViewModel;

/**
 * @template TResponse
 * @template TResource
 * @template TViewModel of RetrieveJsonViewModel
 * @extends ResourceViewModelPresenter<TResponse, TResource, TViewModel>
 */
abstract class RetrieveJsonViewModelPresenter extends ResourceViewModelPresenter
{
    protected function initializeViewModel(mixed $item): RetrieveJsonViewModel
    {
        return new RetrieveJsonViewModel(item: $item);
    }
}
