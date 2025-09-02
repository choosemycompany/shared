<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast;

class ProcessBroadcastViewModel implements BroadcastViewModel
{
    /**
     * @param string|string[] $topics
     */
    public function __construct(
        private readonly string|array $topics,
        public readonly string $status,
    ) {
    }

    /**
     * @return string|string[]
     */
    public function topics(): string|array
    {
        return $this->topics;
    }
}
