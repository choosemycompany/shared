<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

interface Broadcasting
{
    public function broadcast(): void;
}
