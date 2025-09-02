<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Domain\Message\Message;

interface MessageDispatching
{
    public function dispatch(Message $message): void;
}
