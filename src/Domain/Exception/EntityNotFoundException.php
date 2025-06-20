<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Exception;

use ChooseMyCompany\Shared\Domain\Filter\Filter;
use ChooseMyCompany\Shared\Domain\ValueObject\Identifier;
use ChooseMyCompany\Shared\Domain\ValueObject\LegacyIdentifier;
use ChooseMyCompany\Shared\Extension\Assert\Assertion;

final class EntityNotFoundException extends DomainException
{
    public static function fromClassAndFilter(string $entityClass, Filter $filterString): self
    {
        Assertion::classExists($entityClass);

        $message = \sprintf(
            'Entity of type %s not found for the given filter: %s',
            $entityClass,
            $filterString
        );

        return new self($message);
    }

    public static function fromClassAndIdentifier(string $entityClass, Identifier|LegacyIdentifier $identifier): EntityNotFoundException
    {
        Assertion::classExists($entityClass);

        $message = \sprintf(
            'Entity of type %s not found for the given identifier: %s',
            $entityClass,
            $identifier
        );

        return new self($message);
    }

    public static function fromClassAndScalarIdentifier(string $entityClass, string $scalarIdentifier): self
    {
        Assertion::classExists($entityClass);

        $message = \sprintf(
            'Entity of type %s not found for the given scalar identifier: %s',
            $entityClass,
            $scalarIdentifier
        );

        return new self($message);
    }

    public static function fromCriteria(string $criteria): self
    {
        $message = \sprintf(
            'Request yielded no results for the following criteria: %s',
            $criteria
        );

        return new self($message);
    }
}
