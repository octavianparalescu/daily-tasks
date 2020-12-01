<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data;


trait StringableProperties
{
    public function __toString()
    {
        $vars = get_object_vars($this);

        return implode(';', array_map(fn(&$item, $key) => ($item . '-' . $key), array_keys($vars), $vars));
    }
}