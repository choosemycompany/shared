<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Faker;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;

final class FakeProcessProvider implements ProcessProvider
{
    public function __construct(
        private readonly Process $process,
    ) {
    }

    public function provide(): Process
    {
        return $this->process;
    }
}
