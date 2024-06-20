<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use ChooseMyCompany\Shared\Domain\Error\Error;
use ChooseMyCompany\Shared\Domain\Error\ErrorList;
use Assert\InvalidArgumentException;
use Assert\LazyAssertionException as BaseLazyAssertionException;

final class LazyAssertionException extends BaseLazyAssertionException
{
    public static function create(string $message, string $propertyPath = null): self
    {
        return new self('', [new InvalidArgumentException(message: $message, code: 0, propertyPath: $propertyPath)]);
    }

    public function getErrors(): ErrorList
    {
        $errors = new ErrorList();
        foreach ($this->getErrorExceptions() as $errorException) {
            $errors->addError($errorException->getMessage(), $errorException->getPropertyPath());
        }

        return $errors;
    }
}
