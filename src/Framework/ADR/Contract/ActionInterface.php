<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Contract;


interface ActionInterface
{
    public function getPath(): string;

    public function getVerb(): ?string;

    public function getOriginalRequest();
}