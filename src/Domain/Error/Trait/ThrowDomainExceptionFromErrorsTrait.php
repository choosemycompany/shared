<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error\Trait;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;
use ChooseMyCompany\Shared\Domain\Exception\DomainException;

trait ThrowDomainExceptionFromErrorsTrait
{
    /**
     * @throws DomainException
     */
    private function throwDomainExceptionFromErrors(ErrorList $errors): void
    {
        throw DomainException::createFromErrors($errors);
    }
}
