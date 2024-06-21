<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error;

interface ViewModelErrorPresenter extends ErrorPresenter
{
    public function viewModel(): mixed;
}
