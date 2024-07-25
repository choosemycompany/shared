<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Exception;

final class EntityNotFoundException extends DomainException
{
    public static function create(string $entityName, string $entityId): self
    {
        $message = \sprintf('Entity %s not found by id %s', $entityName, $entityId);

        return new self($message);
    }
}
