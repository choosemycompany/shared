<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Shared\Error;

use ChooseMyCompany\Shared\Domain\Error\Error;
use ChooseMyCompany\Shared\Domain\Error\ErrorResponse;

trait ErrorViewModelBuilderTrait
{
    public function buildErrorViewModels(ErrorResponse $errorResponse): array
    {
        return \array_map(
            static fn (Error $error) => new ErrorViewModel($error->message, $error->fieldName),
            \iterator_to_array($errorResponse->errors)
        );
    }
}
