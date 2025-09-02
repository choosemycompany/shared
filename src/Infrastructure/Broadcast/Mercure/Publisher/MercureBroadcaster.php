<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\Broadcast\Mercure\Publisher;

use ChooseMyCompany\Shared\Domain\Service\Broadcasting;
use ChooseMyCompany\Shared\Infrastructure\Broadcast\Mercure\Service\MercureUpdateCreation;
use ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast\BroadcastViewModel;
use Symfony\Component\Mercure\HubInterface;

final class MercureBroadcaster implements Broadcasting
{
    public function __construct(
        private readonly MercureUpdateCreation $updateCreation,
        private readonly HubInterface $hub,
    ) {
    }

    public function broadcast(BroadcastViewModel $viewModel): void
    {
        $update = $this->updateCreation->create($viewModel);

        $this->hub->publish($update);
    }
}
