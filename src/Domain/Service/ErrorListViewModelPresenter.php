<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

interface ErrorListViewModelPresenter extends ErrorListPresenter
{
    public function viewModel(): mixed;
}
