<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Faker;

use ChooseMyCompany\Shared\Domain\State\NotFoundState;
use ChooseMyCompany\Shared\Domain\State\NotFoundStateTrait;

final class FakeNotFoundState implements NotFoundState
{
    use NotFoundStateTrait;

    private bool $proceeded = false;

    public function proceed(): void
    {
        $this->assertNotNotFound();

        $this->proceeded = true;
    }

    public function hasProceeded(): bool
    {
        return $this->proceeded;
    }
}
