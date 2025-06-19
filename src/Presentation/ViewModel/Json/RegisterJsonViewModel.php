<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Json;

class RegisterJsonViewModel implements JsonViewModel
{
    public function __construct(public readonly mixed $item)
    {
    }

    public function getHttpCode(): int
    {
        return 201;
    }
}
