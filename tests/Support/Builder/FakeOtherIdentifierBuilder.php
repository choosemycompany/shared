<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Builder;

use ChooseMyCompany\Shared\Tests\Support\Builder\Trait\WithIdentifierTrait;
use ChooseMyCompany\Shared\Tests\Support\Faker\FakeOtherIdentifier;

final class FakeOtherIdentifierBuilder
{
    use WithIdentifierTrait;

    public function build(): FakeOtherIdentifier
    {
        return FakeOtherIdentifier::fromString($this->identifier);
    }
}
