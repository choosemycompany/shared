<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Json;

final class NoContentJsonViewModel implements JsonViewModel
{
    public function getHttpCode(): int
    {
        return 204;
    }
}
