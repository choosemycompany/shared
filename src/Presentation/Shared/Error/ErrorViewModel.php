<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Shared\Error;

final class ErrorViewModel
{
    public function __construct(public string $error, public ?string $fieldName)
    {
    }
}
