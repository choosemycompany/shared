<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Completion;

use ChooseMyCompany\Shared\Domain\Service\Completion;

final class MultipleCompletion implements Completion
{
    /**
     * @var Completion[]
     */
    private array $completions;

    public function __construct(
        Completion ...$completions,
    ) {
        $this->completions = $completions;
    }

    public function complete(): void
    {
        foreach ($this->completions as $completion) {
            $completion->complete();
        }
    }
}
