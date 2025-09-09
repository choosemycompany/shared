<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Batch;

final class BatchProgress
{
    private int $count = 0;

    public function advance(BatchSize $batchSize): bool
    {
        ++$this->count;

        if ($batchSize->isReachedBy($this->count)) {
            $this->reset();

            return true;
        }

        return false;
    }

    private function reset(): void
    {
        $this->count = 0;
    }
}
