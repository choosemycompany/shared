<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Builder;

use ChooseMyCompany\Shared\Domain\List\ErrorList;
use ChooseMyCompany\Shared\Domain\Response\ErrorListResponse;
use ChooseMyCompany\Shared\Domain\ValueObject\Error;

final class ErrorListResponseBuilder
{
    private array $errors = [];

    public function withErrorMessage(string $errorMessage): self
    {
        $this->errors[] = new Error($errorMessage);

        return $this;
    }

    public function build(): ErrorListResponse
    {
        return new ErrorListResponse(
            errors: new ErrorList(...$this->errors),
        );
    }
}
