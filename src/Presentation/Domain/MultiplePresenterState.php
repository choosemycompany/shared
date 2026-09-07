<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Domain;

use ChooseMyCompany\Shared\Domain\Service\PresenterState;

final class MultiplePresenterState implements PresenterState
{
    /**
     * @var PresenterState[]
     */
    private array $presenterStates;

    public function __construct(
        PresenterState ...$presenterStates,
    ) {
        $this->presenterStates = $presenterStates;
    }

    public function hasBeenPresented(): bool
    {
        foreach ($this->presenterStates as $presenterState) {
            if ($presenterState->hasBeenPresented()) {
                return true;
            }
        }

        return false;
    }
}
