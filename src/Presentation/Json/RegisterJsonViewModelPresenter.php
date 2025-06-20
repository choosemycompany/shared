<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Presentation\Shared\ResourceViewModelPresenter;
use ChooseMyCompany\Shared\Presentation\ViewModel\Json\RegisterJsonViewModel;

/**
 * @template TResponse
 * @template TResource
 * @extends ResourceViewModelPresenter<TResponse, TResource, RegisterJsonViewModel>
 */
abstract class RegisterJsonViewModelPresenter extends ResourceViewModelPresenter
{
    protected function initializeViewModel(mixed $item): RegisterJsonViewModel
    {
        return new RegisterJsonViewModel(item: $item);
    }
}
