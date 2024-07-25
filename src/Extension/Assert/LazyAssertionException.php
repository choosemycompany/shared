<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use ChooseMyCompany\Shared\Domain\Error\ErrorList;
use Assert\InvalidArgumentException;
use Assert\LazyAssertionException as BaseLazyAssertionException;

class LazyAssertionException extends BaseLazyAssertionException
{
    private ErrorList $errors;

    /**
     * @param InvalidArgumentException[] $errors
     * @param mixed                      $message
     */
    public function __construct($message, array $errors)
    {
        $this->errors = new ErrorList();
        $this->collectErrors($errors);

        parent::__construct($message, $errors);
    }

    /**
     * @param InvalidArgumentException[] $errors
     */
    private function collectErrors(array $errors): void
    {
        foreach ($errors as $error) {
            if ($error instanceof LazyAssertionException) {
                $this->collectErrors($error->getErrorExceptions());
            } else {
                $this->errors->addError($error->getMessage(), $error->getPropertyPath());
            }
        }
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
