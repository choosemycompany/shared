<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Filter;

abstract class Filter implements \Stringable
{
    /**
     * @return array<string, ?string>
     */
    abstract protected function toArray(): array;

    private function nonNullProperties(): array
    {
        return \array_filter(
            $this->toArray(),
            static fn ($value) => null !== $value
        );
    }

    public function isEmpty(): bool
    {
        return empty($this->nonNullProperties());
    }

    public function toString(): string
    {
        $nonNullProperties = $this->nonNullProperties();

        return \implode(', ', \array_map(
            static fn ($key, $value) => \sprintf('%s: "%s"', $key, $value),
            \array_keys($nonNullProperties),
            $nonNullProperties
        ));
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
