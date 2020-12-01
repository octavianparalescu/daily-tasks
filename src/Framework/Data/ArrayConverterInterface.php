<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data;


use DailyTasks\Framework\PersistentDatabase\Contract\DbObject;

interface ArrayConverterInterface
{
    public function convertEntityToArray(?DbObject $input): ?array;

    public function convertArrayToEntity(array $input): DbObject;
}