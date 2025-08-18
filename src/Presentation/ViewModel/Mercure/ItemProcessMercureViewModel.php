<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Mercure;

final class ItemProcessMercureViewModel extends ProcessMercureViewModel
{
    public function __construct(
        string $topics,
        string $status,
        public readonly mixed $item,
    ) {
        parent::__construct($topics, $status);
    }
}
