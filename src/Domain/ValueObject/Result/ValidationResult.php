<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject\Result;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;

final class ValidationResult
{
    public function __construct(private readonly bool $isValid, private readonly ErrorList $errors)
    {
    }

    public static function success(): self
    {
        return new self(true, new ErrorList());
    }

    public static function failure(ErrorList $errors): self
    {
        return new self(false, $errors);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function isInvalid(): bool
    {
        return !$this->isValid;
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
