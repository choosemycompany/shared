<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\ValueObject;

use Assert\Assert;

final class WarningLog
{
    private function __construct(
        private readonly string $message,
        private readonly ?\Throwable $exception,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function from(string $message, ?\Throwable $exception = null): self
    {
        Assert::that($message)->notBlank('Warning log must not be blank.');

        return new self($message, $exception);
    }

    public function toString(): string
    {
        return $this->message;
    }

    public function exception(): ?\Throwable
    {
        return $this->exception;
    }
}
