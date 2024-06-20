<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json;

use ChooseMyCompany\Shared\Presentation\Shared\Error\ErrorViewModel;

final class ErrorJsonViewModel implements JsonViewModel
{
    public int $httpCode = 422;

    /** @var ErrorViewModel[] */
    public array $errors;

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
