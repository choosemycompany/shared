<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

interface TransactionalSession
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}
