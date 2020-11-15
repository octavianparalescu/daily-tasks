<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI\TestClasses;


use stdClass;

class TestClassOneLevelDependency
{
    public function __construct(stdClass $class)
    {
    }
}