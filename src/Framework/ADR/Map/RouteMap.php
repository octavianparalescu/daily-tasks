<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Map;


use DailyTasks\Framework\ADR\Contract\RouteInterface;
use DailyTasks\Framework\Data\MapEntity;

class RouteMap extends MapEntity
{
    public function getEntitiesType(): string
    {
        return RouteInterface::class;
    }
}