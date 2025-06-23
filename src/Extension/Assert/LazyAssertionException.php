<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use Assert\InvalidArgumentException;
use Assert\LazyAssertionException as BaseLazyAssertionException;
use ChooseMyCompany\Shared\Domain\List\ErrorList;
use ChooseMyCompany\Shared\Domain\ValueObject\Error;

class LazyAssertionException extends BaseLazyAssertionException
{
    private ErrorList $errors;

    /**
     * @param InvalidArgumentException[] $errors
     */
    public function __construct($message, array $errors)
    {
        $this->errors = new ErrorList(...$this->collectErrors($errors));
        parent::__construct($message, $errors);
    }

    /**
     * @param  InvalidArgumentException[] $errors
     * @return Error[]
     */
    private function collectErrors(array $errors): array
    {
        $result = [];

        foreach ($errors as $error) {
            if ($error instanceof self) {
                $result = [...$result, ...$error->getErrors()->all()];
            } else {
                $result[] = new Error($error->getMessage(), $error->getPropertyPath());
            }
        }

        return $result;
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
