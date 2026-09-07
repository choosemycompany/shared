<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\List;

use ChooseMyCompany\Shared\Domain\ValueObject\Error;

final class ErrorList
{
    /** @var Error[] */
    private array $errors;

    public function __construct(
        Error ...$errors,
    ) {
        $this->errors = $errors;
    }

    /**
     * @return Error[]
     */
    public function all(): array
    {
        return $this->errors;
    }

    public function toString(): string
    {
        return \implode(' | ', \array_map(
            static fn (Error $error): string => $error->toString(),
            $this->errors,
        ));
    }
}
