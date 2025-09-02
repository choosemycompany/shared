<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Service;

use ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast\BroadcastViewModel;

interface Broadcasting
{
    public function broadcast(BroadcastViewModel $viewModel): void;
}
