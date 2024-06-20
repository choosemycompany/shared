<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Builder\Trait;

trait WithIdentifierTrait
{
    private string $identifier = 'c6bfe9a2-6e67-4e3d-a85a-d32d472aa90d';

    public function withIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }
}
