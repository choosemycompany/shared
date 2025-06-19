<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Enum;

enum SortDirection: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
