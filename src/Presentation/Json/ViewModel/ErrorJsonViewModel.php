<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\ViewModel;

use ChooseMyCompany\Shared\Extension\Assert\Assertion;
use ChooseMyCompany\Shared\Presentation\Shared\ViewModel\ErrorViewModel;

final class ErrorJsonViewModel implements JsonViewModel
{
    /**
     * @param ErrorViewModel[] $errors
     */
    public function __construct(public readonly array $errors)
    {
        Assertion::allIsInstanceOf($errors, ErrorViewModel::class);
    }

    public function getHttpCode(): int
    {
        return 422;
    }
}
