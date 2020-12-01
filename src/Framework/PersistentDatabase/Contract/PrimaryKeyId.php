<?php
declare(strict_types=1);

namespace DailyTasks\Framework\PersistentDatabase\Contract;


trait PrimaryKeyId
{
    protected function getPrimaryKeyField(): string
    {
        return 'id';
    }
}