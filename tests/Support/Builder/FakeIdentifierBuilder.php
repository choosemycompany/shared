<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Builder;

use ChooseMyCompany\Shared\Tests\Support\Builder\Trait\WithIdentifierTrait;
use ChooseMyCompany\Shared\Tests\Support\Faker\FakeIdentifier;

final class FakeIdentifierBuilder
{
    use WithIdentifierTrait;

    public function build(): FakeIdentifier
    {
        return FakeIdentifier::fromString($this->identifier);
    }
}
