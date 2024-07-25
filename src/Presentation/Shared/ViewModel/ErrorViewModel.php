<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Shared\ViewModel;

final class ErrorViewModel
{
    public function __construct(public string $error, public ?string $fieldName)
    {
    }
}
