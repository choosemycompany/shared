<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\ViewModel;

final class NoContentJsonViewModel implements JsonViewModel
{
    public int $httpCode = 204;

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
