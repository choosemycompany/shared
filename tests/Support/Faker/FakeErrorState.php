<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Faker;

use ChooseMyCompany\Shared\Domain\State\ErrorState;
use ChooseMyCompany\Shared\Domain\State\ErrorStateTrait;

final class FakeErrorState implements ErrorState
{
    use ErrorStateTrait;

    private bool $mutated = false;

    public function mutate(): void
    {
        $this->assertNoErrors();

        $this->mutated = true;
    }

    public function isMutated(): bool
    {
        return $this->mutated;
    }
}
