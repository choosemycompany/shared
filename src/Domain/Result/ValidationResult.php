<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Result;

use ChooseMyCompany\Shared\Domain\List\ErrorList;

final class ValidationResult implements FailureResult, ResultStatus
{
    private function __construct(
        private readonly bool $valid,
        private readonly ErrorList $errors,
    ) {
    }

    public static function valid(): self
    {
        return new self(true, new ErrorList());
    }

    public static function invalid(ErrorList $errors): self
    {
        return new self(false, $errors);
    }

    public function hasFailed(): bool
    {
        return !$this->valid;
    }

    public function hasSucceeded(): bool
    {
        return $this->valid;
    }

    public function errors(): ErrorList
    {
        return $this->errors;
    }
}
