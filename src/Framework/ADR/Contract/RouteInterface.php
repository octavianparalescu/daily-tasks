<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Contract;


use DailyTasks\Framework\Data\Identifiable;

interface RouteInterface extends Identifiable
{
    public function getPath(): string;

    public function getActionClass(): string;
}