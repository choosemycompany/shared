<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Exception;

use ChooseMyCompany\Shared\Domain\Filter\Filter;
use ChooseMyCompany\Shared\Extension\Assert\Assertion;

final class NonUniqueResultException extends DomainException
{
    public static function fromClassAndFilter(string $entityClass, Filter $filter): self
    {
        Assertion::classExists($entityClass);

        $message = sprintf(
            'Query for entity of type %s with the given filter: %s returned multiple results, but only one or none was expected.',
            $entityClass,
            $filter->toString()
        );

        return new self($message);
    }

    public static function fromClassAndMethod(string $entityClass, string $method): self
    {
        Assertion::classExists($entityClass);

        $message = \sprintf(
            'Query for entity of type %s returned multiple results when calling method %s, but only one or none was expected.',
            $entityClass,
            $method
        );

        return new self($message);
    }

    public static function fromClassAndScalarIdentifier(string $entityClass, string $scalarIdentifier): self
    {
        Assertion::classExists($entityClass);

        $message = \sprintf(
            'Query for entity of type %s with the scalar identifier: %s returned multiple results, but only one or none was expected.',
            $entityClass,
            $scalarIdentifier
        );

        return new self($message);
    }
}
