<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use Assert\AssertionChain as BaseAssertionChain;

/**
 * @method AssertionChain satisfyIdentifier(callable $identifierFactory, callable $existenceCheck, string $propertyPath = null, bool $allowEmpty = false) Asserts that an identifier value satisfies all necessary conditions.
 * @method AssertionChain stringable(string|callable $message = null, string $propertyPath = null) Assert that value is a string.
 */
class AssertionChain extends BaseAssertionChain
{
}
