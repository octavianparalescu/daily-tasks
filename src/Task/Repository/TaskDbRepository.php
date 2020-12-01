<?php
declare(strict_types=1);

namespace DailyTasks\Task\Repository;


use DailyTasks\Framework\Application\Kernel;
use DailyTasks\Framework\PersistentDatabase\Contract\DbConverterInterface;
use DailyTasks\Framework\PersistentDatabase\Contract\ObjectDbRepository;
use DailyTasks\Framework\PersistentDatabase\Contract\PrimaryKeyId;
use DailyTasks\Framework\PersistentDatabase\Database;
use DailyTasks\Task\Converter\TaskDbConverter;
use DailyTasks\Task\Lists\TaskList;
use DateInterval;
use DateTime;

class TaskDbRepository extends ObjectDbRepository
{
    use PrimaryKeyId;

    /**
     * @var TaskDbConverter
     */
    private TaskDbConverter $converter;
    /**
     * @var Database
     */
    private Database $database;

    public function __construct(TaskDbConverter $converter, Database $database)
    {
        $this->converter = $converter;
        $this->database = $database;
    }

    protected function getConverter(): DbConverterInterface
    {
        return $this->converter;
    }

    protected function getDatabase(): Database
    {
        return $this->database;
    }

    protected function getTable(): string
    {
        return 'task';
    }

    public function getListByDueDate(DateTime $dueDate): TaskList
    {
        $queryBuilder = $this->getQueryBuilder();

        $queryBuilder->select('*')
                     ->from($this->getTable())
                     ->where(
                         $queryBuilder->expr()
                                      ->and(
                                          $queryBuilder->expr()
                                                       ->gte('due_date', '?'),
                                          $queryBuilder->expr()
                                                       ->lt('due_date', '?')
                                      )
                     );
        $queryBuilder->setParameter(0, $dueDate->format(Kernel::FORMAT_DATE));
        $queryBuilder->setParameter(
            1,
            $dueDate->add(new DateInterval('P1D'))
                    ->format(Kernel::FORMAT_DATE)
        );

        $rows = $queryBuilder->execute()
                             ->fetchAllAssociative();

        $taskMap = new TaskList();
        foreach ($rows as $row) {
            $taskMap->add(
                $this->getConverter()
                     ->convertFromRowToEntity($row)
            );
        }

        return $taskMap;
    }
}