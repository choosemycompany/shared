<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Faker;

use ChooseMyCompany\Shared\Domain\Service\PresenterState;

final class FakePresenterState implements PresenterState
{
    public function __construct(
        private readonly bool $presented,
    ) {
    }

    public function hasBeenPresented(): bool
    {
        return $this->presented;
    }
}
