<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Presentation\Shared\ResourceViewModelPresenter;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\UpdateJsonViewModel;

/**
 * @template TResponse
 * @template TResource
 * @template TViewModel of UpdateJsonViewModel
 * @extends ResourceViewModelPresenter<TResponse, TResource, TViewModel>
 */
abstract class UpdateJsonViewModelPresenter extends ResourceViewModelPresenter
{
    protected function initializeViewModel(mixed $item): UpdateJsonViewModel
    {
        return new UpdateJsonViewModel(item: $item);
    }
}
