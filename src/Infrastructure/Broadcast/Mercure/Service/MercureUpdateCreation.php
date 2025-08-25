<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\Broadcast\Mercure\Service;

use Symfony\Component\Mercure\Update;

interface MercureUpdateCreation
{
    public function create(): Update;
}
