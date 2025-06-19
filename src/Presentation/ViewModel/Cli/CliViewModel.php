<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Cli;

interface CliViewModel extends \IteratorAggregate
{
    /**
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable;
}
