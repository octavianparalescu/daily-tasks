<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Domain\Map;


use DailyTasks\Framework\Data\MapEntity;
use DailyTasks\Framework\Domain\Entity\Domain;

class DomainMap extends MapEntity
{
    public function getEntitiesType(): string
    {
        return Domain::class;
    }
}