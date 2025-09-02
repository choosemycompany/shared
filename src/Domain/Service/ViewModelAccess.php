<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

/**
 * @template-covariant TViewModel of mixed
 */
interface ViewModelAccess
{
    /**
     * @return TViewModel
     */
    public function viewModel(): mixed;
}
