<?php
declare(strict_types=1);

namespace DailyTasks\Task\Converter;


use DailyTasks\Framework\Application\Kernel;
use DailyTasks\Framework\Data\ListEntity;
use DailyTasks\Framework\PersistentDatabase\Contract\DbConverterInterface;
use DailyTasks\Framework\PersistentDatabase\Contract\DbObject;
use DailyTasks\Task\Entity\Task;
use DailyTasks\Task\Lists\TaskList;
use DateTime;

/**
 * Class TaskDbConverter
 * @todo    Rely on user's timezone
 * @package DailyTasks\Task\Converter
 */
class TaskDbConverter implements DbConverterInterface
{
    public function convertFromRowToEntity(array $row): DbObject
    {
        return new Task(
            (int) $row['id'],
            $row['title'],
            $row['description'] ?? '',
            (bool) $row['completed'],
            DateTime::createFromFormat(Kernel::FORMAT_DATE_TIME, $row['due_date'])
        );
    }

    /**
     * @param DbObject $entity
     *
     * @return array
     */
    public function convertFromEntityToRow(DbObject $entity): array
    {
        /** @var Task $entity */
        return [
            'id' => $entity->getId(),
            'title' => $entity->getTitle(),
            'description' => $entity->getDescription(),
            'completed' => (int) $entity->isCompleted(),
            'due_date' => $entity->getDueDate()
                                 ->format(Kernel::FORMAT_DATE_TIME),
        ];
    }

    public function getListOfEntities(array $dbObjects): ListEntity
    {
        return new TaskList($dbObjects);
    }
}