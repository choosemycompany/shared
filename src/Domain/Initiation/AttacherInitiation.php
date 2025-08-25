<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Initiation;

use ChooseMyCompany\Shared\Domain\Service\Attacher;
use ChooseMyCompany\Shared\Domain\Service\Initiation;

final class AttacherInitiation implements Initiation
{
    public function __construct(
        private readonly Attacher $attacher,
    ) {
    }

    public function initiation(): void
    {
        $this->attacher->attach();
    }
}
