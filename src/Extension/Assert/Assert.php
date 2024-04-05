<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Extension\Assert;

use Assert\Assert as BaseAssert;

final class Assert extends BaseAssert
{
    protected static $lazyAssertionExceptionClass = LazyAssertionException::class;

    protected static $assertionClass = Assertion::class;

    public static function lazy(): LazyAssertion
    {
        $lazyAssertion = new LazyAssertion();

        return $lazyAssertion
            ->setAssertClass(\get_called_class())
            ->setExceptionClass(self::$lazyAssertionExceptionClass)
        ;
    }
}
