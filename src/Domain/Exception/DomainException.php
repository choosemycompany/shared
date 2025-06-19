<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Exception;

use ChooseMyCompany\Shared\Domain\List\ErrorList;

class DomainException extends \Exception
{
    private ErrorList $errors;

    protected function __construct(
        string $message = '',
        ErrorList $errors = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $this->errors = $errors ?? new ErrorList();

        parent::__construct($message, $code, $previous);
    }

    public static function createFromErrors(ErrorList $errors): static
    {
        return new static(errors: $errors);
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
