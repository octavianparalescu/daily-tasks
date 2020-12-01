<?php
declare(strict_types=1);

namespace DailyTasks\Framework\PersistentDatabase\Contract;


use DailyTasks\Framework\Data\ListEntity;

interface DbConverterInterface
{
    public function convertFromRowToEntity(array $row): DbObject;

    public function convertFromEntityToRow(DbObject $entity): array;

    public function getListOfEntities(array $dbObjects): ListEntity;
}