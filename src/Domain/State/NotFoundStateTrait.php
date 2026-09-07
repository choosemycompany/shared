<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\State;

trait NotFoundStateTrait
{
    private bool $notFound = false;

    /**
     * @throws \LogicException
     */
    final public function markNotFound(): void
    {
        if ($this->notFound) {
            throw new \LogicException('Not found already set.');
        }

        $this->notFound = true;
    }

    final public function isNotFound(): bool
    {
        return $this->notFound;
    }

    /**
     * @throws \LogicException
     */
    final protected function assertNotNotFound(): void
    {
        if ($this->notFound) {
            throw new \LogicException('Resource not found.');
        }
    }
}
