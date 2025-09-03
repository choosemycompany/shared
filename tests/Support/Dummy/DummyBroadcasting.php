<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Tests\Support\Dummy;

use ChooseMyCompany\Shared\Domain\Service\Broadcasting;
use ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast\BroadcastViewModel;

final class DummyBroadcasting implements Broadcasting
{
    public bool $broadcastIsCalled = false;

    public function broadcast(BroadcastViewModel $viewModel): void
    {
        $this->broadcastIsCalled = true;
    }
}
