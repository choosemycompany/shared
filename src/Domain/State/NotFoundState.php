<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\State;

interface NotFoundState
{
    public function markNotFound(): void;

    public function isNotFound(): bool;
}
