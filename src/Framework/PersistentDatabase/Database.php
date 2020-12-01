<?php
declare(strict_types=1);

namespace DailyTasks\Framework\PersistentDatabase;


use Doctrine\DBAL\Connection;

class Database
{
    /**
     * @var Connection
     */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }
}