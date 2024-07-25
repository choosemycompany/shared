<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\Json\ViewModel;

interface JsonViewModel
{
    public function getHttpCode(): int;
}
