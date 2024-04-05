<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\ValueObject\Uuid;

interface IdentifierGeneration
{
    public function generate(): Uuid;
}
