<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Completion;

use ChooseMyCompany\Shared\Domain\Batch\BatchProgress;
use ChooseMyCompany\Shared\Domain\Batch\BatchSize;
use ChooseMyCompany\Shared\Domain\Service\Completion;

final class BatchSizeCompletion implements Completion
{
    private function __construct(
        private readonly Completion $innerCompletion,
        private readonly BatchSize $batchSize,
        private readonly BatchProgress $progress,
    ) {
    }

    public static function create(
        Completion $innerCompletion,
        BatchSize $batchSize,
    ): self {
        return new BatchSizeCompletion(
            innerCompletion: $innerCompletion,
            batchSize: $batchSize,
            progress: new BatchProgress(),
        );
    }

    public function complete(): void
    {
        if ($this->progress->advance($this->batchSize)) {
            $this->innerCompletion->complete();
        }
    }
}
