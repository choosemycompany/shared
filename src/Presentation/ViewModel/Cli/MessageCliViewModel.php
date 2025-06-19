<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Presentation\ViewModel\Cli;

final class MessageCliViewModel implements CliViewModel
{
    public function __construct(
        public readonly string $tag,
        public readonly string $message,
    ) {
    }

    /**
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        yield \sprintf('<%s>%s</>', $this->tag, $this->message);
    }
}
