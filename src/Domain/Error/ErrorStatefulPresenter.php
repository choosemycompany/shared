<?php

declare(strict_types=1);

namespace ChooseMyCompany\Shared\Domain\Error;

interface ErrorStatefulPresenter extends ErrorPresenter, ErrorStateChecker
{
    public function getErrors(): ErrorList;
}
