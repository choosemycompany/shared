<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Exception;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;

class DomainException extends \Exception
{
    private ErrorList $errors;

    final private function __construct(ErrorList $errors, string $message = '', \Throwable $previous = null)
    {
        $this->errors = $errors;

        parent::__construct(message: $message, previous: $previous);
    }

    public static function createFromErrors(ErrorList $errors): static
    {
        return new static($errors);
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
