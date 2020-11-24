<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR;


use DailyTasks\Framework\ADR\Contract\ActionInterface;

class CLIAction implements ActionInterface
{
    private array $cliParams;

    public function __construct(array $cliParams)
    {
        $this->cliParams = $cliParams;
    }

    public function getPath(): string
    {
        return $this->cliParams[0];
    }

    public function getOriginalRequest()
    {
        $this->cliParams;
    }

    public function getVerb(): ?string
    {
        return null;
    }
}