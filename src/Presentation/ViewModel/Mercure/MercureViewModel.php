<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Mercure;

interface MercureViewModel
{
    /**
     * @return string|string[]
     */
    public function topics(): string|array;
}
