<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Validation;

use Assert\Assert;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use Assert\LazyAssertion;
use Assert\LazyAssertionException;
use ChooseMyCompany\Shared\Domain\List\ErrorList;
use ChooseMyCompany\Shared\Domain\Result\ValidationResult;
use ChooseMyCompany\Shared\Domain\ValueObject\Error;

final class AssertValidation
{
    public static function validateLazy(callable $assertions): ValidationResult
    {
        $lazy = Assert::lazy();
        $assertions($lazy);

        try {
            $lazy->verifyNow();
        } catch (LazyAssertionException $e) {
            return self::fromLazyErrors($e);
        } catch (InvalidArgumentException $e) {
            return self::fromSimpleError($e);
        }

        return ValidationResult::valid();
    }

    public static function validateSimple(callable $assertions): ValidationResult
    {
        try {
            $assertions();
        } catch (LazyAssertionException $e) {
            return self::fromLazyErrors($e);
        } catch (InvalidArgumentException $e) {
            return self::fromSimpleError($e);
        }

        return ValidationResult::valid();
    }

    public static function validateLazyField(
        LazyAssertion $lazy,
        mixed $value,
        string $field,
        callable $validator
    ): void {
        $error = $validator($value);
        $lazy->that($value, $field)
            ->satisfy(
                fn () => true === $error,
                $error
            );
    }

    public static function validateSimpleField(
        mixed $value,
        string $field,
        callable $validator,
        string $message = null
    ): void {
        $error = $validator($value);
        Assertion::satisfy(
            $value,
            fn () => true === $error,
            $message ?? $error,
            $field
        );
    }

    private static function fromLazyErrors(LazyAssertionException $e): ValidationResult
    {
        $errors = \array_map(
            fn ($violation) => new Error($violation->getMessage(), $violation->getPropertyPath()),
            $e->getErrorExceptions()
        );

        return ValidationResult::invalid(new ErrorList(...$errors));
    }

    private static function fromSimpleError(InvalidArgumentException $e): ValidationResult
    {
        return ValidationResult::invalid(
            new ErrorList(
                new Error($e->getMessage(), $e->getPropertyPath())
            )
        );
    }
}
