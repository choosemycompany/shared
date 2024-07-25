<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use Assert\Assertion as BaseAssertion;
use Assert\AssertionFailedException;

/**
 * @method static bool allSatisfyIdentifier(mixed[] $values, callable $identifierFactory, callable $existenceCheck, string $propertyPath = null, bool $allowEmpty = false)    Asserts that an identifier value satisfies all necessary conditions for all values.
 * @method static bool allStringable(mixed[] $value, string|callable $message = null, string $propertyPath = null)                                                            Assert that value is stringable for all values.
 * @method static bool nullOrStringable(mixed|null $value, string|callable $message = null, string $propertyPath = null)                                                      Assert that value is stringable or that the value is null.
 * @method static bool nullOrSatisfyIdentifier(mixed[] $values, callable $identifierFactory, callable $existenceCheck, string $propertyPath = null, bool $allowEmpty = false) Asserts that an identifier value satisfies all necessary conditions or that the value is null.
 */
class Assertion extends BaseAssertion
{
    protected static $exceptionClass = InvalidArgumentException::class;

    /**
     * Checks if the value is "stringable" and not a boolean.
     *
     * @param mixed       $value        the value to check
     * @param string|null $message      the error message
     * @param string|null $propertyPath the property path (for error messages)
     *
     * @throws AssertionFailedException if the value is not "stringable" or is a boolean
     */
    public static function stringable($value, string $message = null, string $propertyPath = null): bool
    {
        if (is_bool($value) || (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString')))) {
            $message = \sprintf(
                self::generateMessage(
                    $message ?? 'Value "%s" is expected to be stringable and not a boolean, but "%s" given.'
                ),
                self::stringify($value),
                gettype($value)
            );
            $propertyPath = $propertyPath ?? 'value';

            /** @var AssertionFailedException $exception */
            $exception = self::createException($value, $message, self::INVALID_STRING, $propertyPath);

            throw $exception;
        }

        return true;
    }

    /**
     * Asserts that an identifier value satisfies all necessary conditions.
     *
     * @param mixed    $value             the value of the identifier to check
     * @param string   $propertyPath      the property path for error messages
     * @param callable $identifierFactory a factory function to create the identifier
     * @param callable $existenceCheck    a function to check the existence of the identifier
     * @param bool     $allowEmpty        whether an empty identifier is allowed
     *
     * @throws AssertionFailedException if the identifier is not valid
     *
     * @example
     *   Assertion::assertValidIdentifier(
     *       $value,
     *       fn($identifierValue) => UserIdentifier::tryFrom($identifierValue),
     *       fn($identifier) => $userRepository->exists($identifier)
     *   );
     */
    public static function satisfyIdentifier(
        mixed $value,
        callable $identifierFactory,
        callable $existenceCheck,
        string $propertyPath = null,
        bool $allowEmpty = false
    ): bool {
        if ($allowEmpty && '' === $value) {
            return true;
        }

        self::notBlank($value, 'validation_error.missing', $propertyPath);
        self::stringable($value, 'validation_error.stringable', $propertyPath);

        $identifier = $identifierFactory((string) $value);
        self::notNull($identifier, 'validation_error.invalid_identifier', $propertyPath);
        self::true($existenceCheck($identifier), 'validation_error.not_found', $propertyPath);

        return true;
    }
}
