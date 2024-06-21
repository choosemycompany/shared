<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use Assert\Assertion as BaseAssertion;

final class Assertion extends BaseAssertion
{
    protected static $exceptionClass = InvalidArgumentException::class;
}
