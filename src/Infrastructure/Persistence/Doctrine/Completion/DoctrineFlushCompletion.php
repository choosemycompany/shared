<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\Persistence\Doctrine\Completion;

use ChooseMyCompany\Shared\Domain\Service\Completion;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineFlushCompletion implements Completion
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function complete(): void
    {
        $unitOfWork = $this->entityManager->getUnitOfWork();
        $unitOfWork->computeChangeSets();

        if (
            [] === $unitOfWork->getScheduledEntityInsertions()
            && [] === $unitOfWork->getScheduledEntityUpdates()
            && [] === $unitOfWork->getScheduledEntityDeletions()
        ) {
            return;
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}
