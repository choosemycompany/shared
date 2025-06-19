<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Exception;

/**
 * @deprecated It is not a domain exception
 */
final class ViewModelContainsErrorsException extends \LogicException
{
    public function __construct()
    {
        parent::__construct('The view model contains errors and cannot be presented.');
    }
}
