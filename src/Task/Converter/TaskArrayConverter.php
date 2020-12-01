<?php
declare(strict_types=1);

namespace DailyTasks\Task\Converter;


use DailyTasks\Framework\Application\Kernel;
use DailyTasks\Framework\Data\ArrayConverterInterface;
use DailyTasks\Framework\PersistentDatabase\Contract\DbObject;
use DailyTasks\Task\Entity\Task;
use DailyTasks\Task\Lists\TaskList;
use DateTime;

class TaskArrayConverter implements ArrayConverterInterface
{
    public function convertEntityToArray(?DbObject $task): ?array
    {
        if (!$task) {
            return null;
        }

        /** @var Task $task */
        return [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'due_date' => $task->getDueDate()
                               ->format(Kernel::FORMAT_DATE_TIME),
            'completed' => $task->isCompleted(),
        ];
    }

    public function convertArrayToEntity(array $taskArray): DbObject
    {
        return new Task(
            $taskArray['id'] ?? null,
            $taskArray['title'],
            $taskArray['description'] ?? '',
            (bool) $taskArray['completed'],
            DateTime::createFromFormat(Kernel::FORMAT_DATE_TIME, $taskArray['due_date'])
        );
    }

    /**
     * @param TaskList $taskList
     *
     * @return array
     */
    public function convertEntityListToArray(TaskList $taskList): array
    {
        $return = [];
        /** @var Task $task */
        foreach ($taskList as $task) {
            $return [] = $this->convertEntityToArray($task);
        }

        return $return;
    }
}