<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Initiation;

use ChooseMyCompany\Shared\Domain\Service\Initiation;

final class MultipleInitiation implements Initiation
{
    /**
     * @var Initiation[]
     */
    private array $initiations;

    public function __construct(
        Initiation ...$initiation,
    ) {
        $this->initiations = $initiation;
    }

    public function initiation(): void
    {
        foreach ($this->initiations as $initiation) {
            $initiation->initiation();
        }
    }
}
