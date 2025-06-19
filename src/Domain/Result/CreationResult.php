<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Result;

use ChooseMyCompany\Shared\Domain\List\ErrorList;

/**
 * @template T of object
 */
final class CreationResult implements FailureResult, ResultStatus
{
    /**
     * @param T|null $resource
     */
    private function __construct(
        private readonly bool $successful,
        private readonly mixed $resource,
        private readonly ErrorList $errors,
    ) {
    }

    /**
     * @template TResource of object
     * @param  TResource       $resource
     * @return self<TResource>
     */
    public static function success(mixed $resource): self
    {
        return new self(true, $resource, new ErrorList());
    }

    /**
     * @return self<never>
     */
    public static function failure(ErrorList $errors): self
    {
        /* @var self<never> */
        return new self(false, null, $errors);
    }

    public function hasFailed(): bool
    {
        return !$this->successful;
    }

    public function hasSucceeded(): bool
    {
        return $this->successful;
    }

    /**
     * @return T
     * @throws \RuntimeException
     */
    public function resource(): mixed
    {
        if (!$this->successful || null === $this->resource) {
            throw new \RuntimeException('Cannot get created object from failed result');
        }

        return $this->resource;
    }

    public function errors(): ErrorList
    {
        return $this->errors;
    }
}
