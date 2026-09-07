<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

final class Error
{
    public function __construct(
        public readonly string $message,
        public readonly ?string $field = null,
    ) {
    }

    public function toString(): string
    {
        return null === $this->field ? $this->message : \sprintf('%s: %s', $this->field, $this->message);
    }
}
