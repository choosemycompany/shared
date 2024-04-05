<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error;

final class ErrorResponse
{
    public function __construct(public readonly ErrorList $errors)
    {
    }
}
