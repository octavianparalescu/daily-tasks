<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Contract;


use DailyTasks\Framework\Data\Identifiable;
use DailyTasks\Framework\Domain\Entity\Domain;

interface RouteInterface extends Identifiable
{
    public function getPath(): string;

    public function getActionClass(): string;

    public function getDomain(): Domain;
}