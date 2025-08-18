<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Infrastructure\Notification\Mercure\Publisher;

use ChooseMyCompany\Shared\Domain\Notification\ProcessNotification;
use ChooseMyCompany\Shared\Domain\Service\ProcessNotificationPublishing;
use ChooseMyCompany\Shared\Presentation\ViewModel\Mercure\MercureViewModel;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

final class MercureProcessNotificationPublisher implements ProcessNotificationPublishing
{
    public function __construct(
        private readonly HubInterface $hub,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function publish(ProcessNotification $notification): void
    {
        $viewModel = $notification->process->viewModel();
        if (!$viewModel instanceof MercureViewModel) {
            return;
        }

        $update = new Update(
            topics: $viewModel->topics(),
            data: $this->serializer->serialize($viewModel, 'json'),
        );

        $this->hub->publish($update);
    }
}
