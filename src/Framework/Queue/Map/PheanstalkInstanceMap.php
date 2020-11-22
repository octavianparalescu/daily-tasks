<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Queue\Map;


use DailyTasks\Framework\Data\MapEntity;
use DailyTasks\Framework\Queue\Entity\PheanstalkInstance;

class PheanstalkInstanceMap extends MapEntity
{
    public function getEntitiesType(): string
    {
        return PheanstalkInstance::class;
    }
}