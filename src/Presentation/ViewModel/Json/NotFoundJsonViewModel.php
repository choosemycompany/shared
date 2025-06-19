<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Json;

final class NotFoundJsonViewModel implements JsonViewModel
{
    public function __construct(
        public readonly string $resource,
        public readonly string $filter,
        public readonly string $message,
    ) {
    }

    public function getHttpCode(): int
    {
        return 404;
    }
}
