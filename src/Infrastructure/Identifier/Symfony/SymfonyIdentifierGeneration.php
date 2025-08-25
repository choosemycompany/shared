<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\Identifier\Symfony;

use ChooseMyCompany\Shared\Domain\Service\IdentifierGeneration;
use ChooseMyCompany\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Uid\Factory\UuidFactory;

final class SymfonyIdentifierGeneration implements IdentifierGeneration
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
    ) {
    }

    public function generate(): Uuid
    {
        return new Uuid($this->uuidFactory->generate());
    }
}
