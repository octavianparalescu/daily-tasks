<?php
declare(strict_types=1);

namespace DailyTasks\Framework\PersistentDatabase\Contract;


use DailyTasks\Framework\Data\Identifiable;
use DailyTasks\Framework\Data\ListEntity;
use DailyTasks\Framework\PersistentDatabase\Database;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class ObjectDbRepository
{
    abstract protected function getPrimaryKeyField(): string;

    abstract protected function getConverter(): DbConverterInterface;

    abstract protected function getDatabase(): Database;

    abstract protected function getTable(): string;

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->getDatabase()
                    ->getConnection()
                    ->createQueryBuilder();
    }

    /**
     * @param $primaryKey
     *
     * @return DbObject
     * @throws Exception
     */
    public function fetch($primaryKey): ?DbObject
    {
        $queryBuilder = $this->getQueryBuilder();

        $queryBuilder->select('*')
                     ->from($this->getTable())
                     ->where(
                         $this->getPrimaryKeyField() . ' = ' . $queryBuilder->createPositionalParameter($primaryKey)
                     )
                     ->setMaxResults(1);

        $rows = $queryBuilder->execute()
                             ->fetchAllAssociative();

        if (isset($rows[0])) {
            return $this->getConverter()
                        ->convertFromRowToEntity($rows[0]);
        } else {
            return null;
        }
    }

    public function delete($primaryKey): bool
    {
        $queryBuilder = $this->getQueryBuilder();

        $queryBuilder->delete($this->getTable())
                     ->where(
                         $this->getPrimaryKeyField() . ' = ' . $queryBuilder->createPositionalParameter($primaryKey)
                     );

        $result = $queryBuilder->execute();

        return (bool) $result;
    }

    public function save(DbObject $dbObject): ?DbObject
    {
        $queryBuilder = $this->getQueryBuilder();

        $objectInRowFormat = $this->getConverter()
                                  ->convertFromEntityToRow($dbObject);

        $queryBuilder->insert($this->getTable());

        $paramNo = 0;
        foreach ($objectInRowFormat as $key => $value) {
            $queryBuilder->setValue($key, '?')
                         ->setParameter($paramNo++, $value);
        }

        $result = $queryBuilder->execute();

        if ($result) {
            $id = $this->getDatabase()
                       ->getConnection()
                       ->lastInsertId();

            return $this->fetch($id);
        } else {
            return null;
        }
    }

    /**
     * @param DbObject|Identifiable $dbObject
     *
     * @return DbObject|null
     * @throws Exception
     */
    public function update(DbObject $dbObject)
    {
        $queryBuilder = $this->getQueryBuilder();

        $queryBuilder->update($this->getTable());

        $objectInRowFormat = $this->getConverter()
                                  ->convertFromEntityToRow($dbObject);

        $paramNo = 0;
        foreach ($objectInRowFormat as $key => $value) {
            $queryBuilder->set($key, '?')
                         ->setParameter($paramNo++, $value);
        }

        $queryBuilder->where(
            $this->getPrimaryKeyField() . ' = ?'
        );

        $queryBuilder->setParameter($paramNo, $dbObject->getKey());

        $queryBuilder->execute();

        $id = $dbObject->getKey();

        return $this->fetch($id);
    }

    public function fetchAll(): ListEntity
    {
        $queryBuilder = $this->getQueryBuilder();

        $queryBuilder->select('*')
                     ->from($this->getTable());

        $rows = $queryBuilder->execute()
                             ->fetchAllAssociative();

        $entitiesList = [];
        foreach ($rows as $row) {
            $entitiesList [] = $this->getConverter()
                                    ->convertFromRowToEntity($row);
        }

        return $this->getConverter()
                    ->getListOfEntities($entitiesList);
    }
}