<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Exception;

final class ValueNotDefinedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Value is not defined.');
    }
}
