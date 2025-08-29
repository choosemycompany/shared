<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Initiation;

use ChooseMyCompany\Shared\Domain\Service\Initiation;
use ChooseMyCompany\Shared\Domain\Service\ResetState;

final class MultipleResetStateInitiation implements Initiation
{
    /**
     * @var ResetState[]
     */
    private array $resetState;

    public function __construct(
        ResetState ...$resetState,
    ) {
        $this->resetState = $resetState;
    }

    public function initiation(): void
    {
        foreach ($this->resetState as $resetState) {
            $resetState->reset();
        }
    }
}
