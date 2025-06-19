<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

interface AccessDeniedViewModelPresenter extends AccessDeniedPresenter
{
    public function viewModel(): mixed;
}
