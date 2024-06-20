<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use Assert\InvalidArgumentException as BaseInvalidArgumentException;
use ChooseMyCompany\Shared\Domain\Error\ErrorList;

final class InvalidArgumentException extends BaseInvalidArgumentException
{
    public function getErrors(): ErrorList
    {
        $errors = new ErrorList();
        $errors->addError($this->getMessage(), $this->getPropertyPath());

        return $errors;
    }
}
