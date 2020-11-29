<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Config\Map;


use DailyTasks\Framework\Config\ConfigContainer;
use DailyTasks\Framework\Data\MapEntity;

class ConfigContainerMap extends MapEntity
{
    public function getEntitiesType(): string
    {
        return ConfigContainer::class;
    }
}