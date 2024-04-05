<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error;

final class Error
{
    public function __construct(public readonly string $message, public readonly ?string $fieldName = null)
    {
    }
}
