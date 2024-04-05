<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

interface EventDispatching
{
    public function dispatch(object $event, string $eventName = null): void;
}
