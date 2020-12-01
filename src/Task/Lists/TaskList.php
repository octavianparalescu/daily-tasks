<?php
declare(strict_types=1);

namespace DailyTasks\Task\Lists;


use DailyTasks\Framework\Data\ListEntity;
use DailyTasks\Task\Entity\Task;

class TaskList extends ListEntity
{
    public function getEntitiesType(): string
    {
        return Task::class;
    }
}