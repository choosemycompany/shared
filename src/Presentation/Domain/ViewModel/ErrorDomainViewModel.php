<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Domain\ViewModel;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;

final class ErrorDomainViewModel extends DomainViewModel
{
    public function __construct(public readonly ErrorList $errors)
    {
    }
}
