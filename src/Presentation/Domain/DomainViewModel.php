<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Domain;

abstract class DomainViewModel
{
    public function isErrorViewModel(): bool
    {
        return $this instanceof ErrorDomainViewModel;
    }
}
