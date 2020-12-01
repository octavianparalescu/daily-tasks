<?php
declare(strict_types=1);

namespace DailyTasks\Task\Converter;

use DailyTasks\Framework\Application\Kernel;
use DailyTasks\Task\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class TaskDbConverterTest
 * @todo    Check if can be merged with TaskArrayConverter
 * @covers  \DailyTasks\Task\Converter\TaskDbConverter
 * @uses    \DailyTasks\Task\Entity\Task
 * @package Task\Converter
 */
class TaskDbConverterTest extends TestCase
{
    public function testConvertFromRowToEntityModifiers()
    {
        $taskArrayConverter = new TaskDbConverter();
        $id = '1';
        $title = 'test title';
        $description = null;
        $completed = 1;
        $dueDate = new DateTime('2020-12-01');
        $row = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'completed' => $completed,
            'due_date' => $dueDate->format(Kernel::FORMAT_DATE_TIME),
        ];
        $this->assertEquals(
            new Task(
                (int) $id, $title, '', (bool) $completed, $dueDate
            ),
            $taskArrayConverter->convertFromRowToEntity(
                $row
            )
        );
    }

    public function testConvertFromEntityToRow()
    {
        $taskArrayConverter = new TaskDbConverter();
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
            $taskArrayConverter->convertFromEntityToRow(
                new Task(
                    $id, $title, $description, $completed, $dueDate
                )
            )
        );
    }
}
