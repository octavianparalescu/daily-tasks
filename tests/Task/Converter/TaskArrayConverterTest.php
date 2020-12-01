<?php
declare(strict_types=1);

namespace DailyTasks\Task\Converter;

use DailyTasks\Framework\Application\Kernel;
use DailyTasks\Task\Entity\Task;
use DailyTasks\Task\Lists\TaskList;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class TaskArrayConverterTest
 * @covers  \DailyTasks\Task\Converter\TaskArrayConverter
 * @uses    \DailyTasks\Task\Entity\Task
 * @uses    \DailyTasks\Framework\Data\ListEntity
 * @package Task\Converter
 */
class TaskArrayConverterTest extends TestCase
{
    public function testConvertEntityToArray()
    {
        $taskArrayConverter = new TaskArrayConverter();
        $id = 1;
        $title = 'test title';
        $description = 'test description';
        $completed = true;
        $dueDate = new DateTime('2020-12-01');
        $this->assertEquals(
            [
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'completed' => $completed,
                'due_date' => $dueDate->format(Kernel::FORMAT_DATE_TIME),
            ],
            $taskArrayConverter->convertEntityToArray(
                new Task(
                    $id, $title, $description, $completed, $dueDate
                )
            )
        );
    }

    public function testConvertArrayToEntity()
    {
        $taskArrayConverter = new TaskArrayConverter();
        $id = 1;
        $title = 'test title';
        $description = 'test description';
        $completed = true;
        $dueDate = new DateTime('2020-12-01');
        $this->assertEquals(
            new Task(
                $id, $title, $description, $completed, $dueDate
            ),
            $taskArrayConverter->convertArrayToEntity(
                [
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'completed' => $completed,
                    'due_date' => $dueDate->format(Kernel::FORMAT_DATE_TIME),
                ]
            )
        );
    }

    public function testConvertArrayToEntityModifiers()
    {
        $taskArrayConverter = new TaskArrayConverter();
        $id = null;
        $title = 'test title';
        $description = null;
        $completed = 1;
        $dueDate = new DateTime('2020-12-01');
        $this->assertEquals(
            new Task(
                null, $title, '', true, $dueDate
            ),
            $taskArrayConverter->convertArrayToEntity(
                [
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'completed' => $completed,
                    'due_date' => $dueDate->format(Kernel::FORMAT_DATE_TIME),
                ]
            )
        );
    }

    public function testConvertEntityListToArray()
    {
        $taskArrayConverter = new TaskArrayConverter();
        $id = 1;
        $title = 'test title';
        $description = 'test description';
        $completed = true;
        $dueDate = new DateTime('2020-12-01');
        $task1 = new Task(
            $id, $title, $description, $completed, $dueDate
        );
        $task2 = new Task(
            $id + 1, $title, $description, $completed, $dueDate
        );
        $list = $taskArrayConverter->convertEntityListToArray(new TaskList([$task1, $task2]));
        $this->assertEquals(
            [
                [
                    'id' => $id,
                    'title' => $title,
                    'description' => $description,
                    'completed' => $completed,
                    'due_date' => $dueDate->format(Kernel::FORMAT_DATE_TIME),
                ],
                [
                    'id' => $id + 1,
                    'title' => $title,
                    'description' => $description,
                    'completed' => $completed,
                    'due_date' => $dueDate->format(Kernel::FORMAT_DATE_TIME),
                ],
            ],
            $list
        );
    }
}
