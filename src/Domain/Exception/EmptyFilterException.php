<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Exception;

use ChooseMyCompany\Shared\Domain\Filter\Filter;

final class EmptyFilterException extends DomainException
{
    public static function fromMethodAndFilter(
        string $currentMethod,
        Filter $filter
    ): self {
        $message = \sprintf(
            'Missing values into filter %s to %s',
            $filter::class,
            $currentMethod
        );

        return new self($message);
    }

    public static function fromMessage(string $message): EmptyFilterException
    {
        return new self($message);
    }
}
