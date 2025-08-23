<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\Broadcast\Mercure\Factory;

use ChooseMyCompany\Shared\Domain\Service\ProcessProvider;
use ChooseMyCompany\Shared\Infrastructure\Broadcast\Mercure\Service\MercureUpdateCreation;
use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\MercureViewModel;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

final class MercureProcessUpdateFactory implements MercureUpdateCreation
{
    public function __construct(
        private readonly ProcessProvider $processProvider,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws \LogicException
     */
    public function create(): Update
    {
        $viewModel = $this->processProvider->provide()->viewModel();
        if (!$viewModel instanceof MercureViewModel) {
            throw new \LogicException('The view model must be an instance of '.MercureViewModel::class);
        }

        return new Update(
            topics: $viewModel->topics(),
            data: $this->serializer->serialize($viewModel, 'json'),
        );
    }
}
