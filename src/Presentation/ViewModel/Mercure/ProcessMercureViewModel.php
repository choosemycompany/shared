<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Mercure;

use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\MercureViewModel;

class ProcessMercureViewModel implements MercureViewModel
{
    public function __construct(
        private readonly string $topics,
        public readonly string $status,
    ) {
    }

    public function topics(): string
    {
        return $this->topics;
    }
}
