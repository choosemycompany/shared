<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

/**
 * @template TViewModel
 */
interface ViewModelAccess
{
    /**
     * @return TViewModel
     */
    public function viewModel(): mixed;
}
