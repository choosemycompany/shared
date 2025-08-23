<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Attacher;

use ChooseMyCompany\Shared\Domain\Service\Attacher;

final class MultipleAttacher implements Attacher
{
    /**
     * @var Attacher[]
     */
    private array $attachers;

    public function __construct(
        Attacher ...$attachers,
    ) {
        $this->attachers = $attachers;
    }

    public function attach(): void
    {
        foreach ($this->attachers as $attacher) {
            $attacher->attach();
        }
    }
}
