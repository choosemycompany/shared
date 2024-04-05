<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error;

final class ErrorList implements \IteratorAggregate
{
    /** @var Error[] */
    private array $errors = [];

    public function addError(string $message, string $fieldName = null): void
    {
        $this->errors[] = new Error($message, $fieldName);
    }

    public function addErrors(ErrorList $errorList): void
    {
        foreach ($errorList as $error) {
            $this->addError($error->message, $error->fieldName);
        }
    }

    public function isEmpty(): bool
    {
        return 0 === \count($this->errors);
    }

    /**
     * @return \ArrayIterator<Error>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->errors);
    }
}
