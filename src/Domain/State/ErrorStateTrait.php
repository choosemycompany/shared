<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\State;

use ChooseMyCompany\Shared\Domain\List\ErrorList;

trait ErrorStateTrait
{
    private ?ErrorList $errors = null;

    /**
     * @throws \LogicException
     */
    final public function setErrors(ErrorList $errors): void
    {
        if (null !== $this->errors) {
            throw new \LogicException('Errors already set.');
        }

        $this->errors = $errors;
    }

    final public function hasErrors(): bool
    {
        return null !== $this->errors;
    }

    final public function hasNoErrors(): bool
    {
        return null === $this->errors;
    }

    /**
     * @throws \LogicException
     */
    final public function errors(): ErrorList
    {
        return $this->errors
            ?? throw new \LogicException('Errors not set.');
    }

    /**
     * @throws \LogicException
     */
    final protected function assertNoErrors(): void
    {
        if ($this->hasErrors()) {
            throw new \LogicException('Cannot mutate state when errors are present.');
        }
    }
}
