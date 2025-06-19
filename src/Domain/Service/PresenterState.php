<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

interface PresenterState
{
    public function hasBeenPresented(): bool;
}
