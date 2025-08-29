<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Process;

enum ProcessState: string
{
    case Initiated = 'initiated';

    case Started = 'started';

    case InProgress = 'in_progress';

    case Completed = 'completed';

    case Failed = 'failed';

    public function isInitiated(): bool
    {
        return ProcessState::Initiated == $this;
    }

    public function isStarted(): bool
    {
        return ProcessState::Started == $this;
    }

    public function isInProgress(): bool
    {
        return ProcessState::InProgress == $this;
    }

    public function isCompleted(): bool
    {
        return ProcessState::Completed == $this;
    }

    public function isFailed(): bool
    {
        return ProcessState::Failed == $this;
    }

    public function isFinalized(): bool
    {
        return $this->isCompleted() || $this->isFailed();
    }

    public function toString(): string
    {
        return $this->value;
    }
}
