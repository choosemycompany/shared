<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Message;

interface Message
{
    public function getRoutingKey(): string;
}
