<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data;


interface Identifiable extends Immutable
{
    /**
     * Can be a string for simple keys or an object for keeping track
     * of composed keys
     * @return string|ComposedKey
     */
    public function getKey();
}