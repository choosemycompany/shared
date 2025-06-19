<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Cli;

use ChooseMyCompany\Shared\Extension\Assert\Assertion;
use ChooseMyCompany\Shared\Presentation\ViewModel\Shared\ErrorViewModel;

final class ErrorCliViewModel implements CliViewModel
{
    /**
     * @param ErrorViewModel[] $errors
     */
    public function __construct(public readonly array $errors)
    {
        Assertion::allIsInstanceOf($errors, ErrorViewModel::class);
    }

    /**
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->errors as $error) {
            yield \sprintf('<error>%s: %s</>', $error->field, $error->message);
        }
    }
}
