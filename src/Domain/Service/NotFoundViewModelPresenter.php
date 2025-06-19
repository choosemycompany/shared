<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

interface NotFoundViewModelPresenter extends NotFoundPresenter
{
    public function viewModel(): mixed;
}
