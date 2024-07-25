<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use Assert\Assert as BaseAssert;

class Assert extends BaseAssert
{
    protected static $lazyAssertionExceptionClass = LazyAssertionException::class;

    protected static $assertionClass = Assertion::class;

    public static function that($value, $defaultMessage = null, string $defaultPropertyPath = null): AssertionChain
    {
        $assertionChain = new AssertionChain($value, $defaultMessage, $defaultPropertyPath);

        return $assertionChain->setAssertionClassName(self::$assertionClass);
    }

    public static function lazy(): LazyAssertion
    {
        $lazyAssertion = new LazyAssertion();

        return $lazyAssertion
            ->setAssertClass(\get_called_class())
            ->setExceptionClass(self::$lazyAssertionExceptionClass)
        ;
    }
}
