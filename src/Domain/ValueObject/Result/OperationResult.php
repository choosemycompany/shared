<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject\Result;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;

final class OperationResult
{
    private function __construct(
        private readonly bool $success,
        private readonly mixed $data,
        private readonly ErrorList $errors
    ) {
    }

    public static function success($data): self
    {
        return new self(true, $data, new ErrorList());
    }

    public static function failure(ErrorList $errors): self
    {
        return new self(false, null, $errors);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isFailure(): bool
    {
        return !$this->success;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
