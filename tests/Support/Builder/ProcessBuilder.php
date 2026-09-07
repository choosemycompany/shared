<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Builder;

use ChooseMyCompany\Shared\Domain\Process\Process;
use ChooseMyCompany\Shared\Domain\Process\ProcessIdentifier;
use ChooseMyCompany\Shared\Tests\Support\Builder\Trait\WithIdentifierTrait;

final class ProcessBuilder
{
    use WithIdentifierTrait;

    public function build(): Process
    {
        return Process::initiated(ProcessIdentifier::from($this->identifier));
    }
}
