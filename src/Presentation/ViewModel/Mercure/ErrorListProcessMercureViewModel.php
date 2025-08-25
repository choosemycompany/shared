<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Mercure;

use ChooseMyCompany\Shared\Presentation\ViewModel\Shared\ErrorViewModel;

final class ErrorListProcessMercureViewModel extends ProcessMercureViewModel
{
    /**
     * @param string|string[] $topics
     * @param ErrorViewModel[] $errors
     */
    public function __construct(
        string|array $topics,
        string $status,
        public array $errors,
    ) {
        parent::__construct($topics, $status);
    }
}
