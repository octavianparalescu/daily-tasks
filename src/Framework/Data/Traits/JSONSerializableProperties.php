<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Traits;


trait JSONSerializableProperties
{
    /**
     * @return false|string
     */
    public function jsonSerialize()
    {
        return json_encode(get_object_vars($this));
    }
}