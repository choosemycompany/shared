<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Shared;

final class ErrorViewModel
{
    public function __construct(
        public string $message,
        public string $field
    ) {
    }
}
