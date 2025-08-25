<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Broadcast;

interface BroadcastViewModel
{
    /**
     * @return string|string[]
     */
    public function topics(): string|array;
}
