<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast;

final class ItemProcessBroadcastViewModel extends ProcessBroadcastViewModel
{
    /**
     * @param string|string[] $topics
     */
    public function __construct(
        string|array $topics,
        string $status,
        public readonly mixed $item,
    ) {
        parent::__construct($topics, $status);
    }
}
