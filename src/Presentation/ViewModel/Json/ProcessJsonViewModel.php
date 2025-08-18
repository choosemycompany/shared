<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Json;

final class ProcessJsonViewModel implements JsonViewModel
{
    public function __construct(
        public readonly string $identifier,
        public readonly string $status,
    ) {
    }

    public function getHttpCode(): int
    {
        return 200;
    }
}
