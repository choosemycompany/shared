<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\Broadcast\Mercure\Factory;

use ChooseMyCompany\Shared\Infrastructure\Broadcast\Mercure\Service\MercureUpdateCreation;
use ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast\BroadcastViewModel;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

final class MercureUpdateFactory implements MercureUpdateCreation
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function create(BroadcastViewModel $viewModel): Update
    {
        return new Update(
            topics: $viewModel->topics(),
            data: $this->serializer->serialize($viewModel, 'json'),
        );
    }
}
