<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Process;

final class Process
{
    private ProcessState $state = ProcessState::Initiated;

    /**
     * @var array<\Closure(Process): void>
     */
    private array $stateChangedCallbacks = [];

    private mixed $viewModel;

    public function __construct(
        public readonly ProcessIdentifier $identifier,
    ) {
    }

    public static function initiated(ProcessIdentifier $identifier): self
    {
        return new self($identifier);
    }

    public function started(): void
    {
        $this->setState(ProcessState::Started);
    }

    public function inProgress(): void
    {
        $this->setState(ProcessState::InProgress);
    }

    public function completed(): void
    {
        $this->setState(ProcessState::Completed);
    }

    public function failed(): void
    {
        $this->setState(ProcessState::Failed);
    }

    public function hasStateFailed(): bool
    {
        return $this->state->isFailed();
    }

    public function state(): ProcessState
    {
        return $this->state;
    }

    private function setState(ProcessState $state): void
    {
        $this->state = $state;
        $this->triggerStateChangedCallbacks();
    }

    /**
     * @param \Closure(Process): void $callback
     */
    public function onStateChanged(\Closure $callback): void
    {
        $this->stateChangedCallbacks[] = $callback;
    }

    private function triggerStateChangedCallbacks(): void
    {
        foreach ($this->stateChangedCallbacks as $callback) {
            $callback($this);
        }
    }

    public function withViewModel(mixed $viewModel): void
    {
        $this->viewModel = $viewModel;
    }

    public function viewModel(): mixed
    {
        return $this->viewModel;
    }
}
