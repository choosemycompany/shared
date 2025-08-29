<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\Broadcast\Mercure\Service;

use ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast\BroadcastViewModel;
use Symfony\Component\Mercure\Update;

interface MercureUpdateCreation
{
    public function create(BroadcastViewModel $viewModel): Update;
}
