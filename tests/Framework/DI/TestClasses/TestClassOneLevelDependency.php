<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI\TestClasses;


class TestClassOneLevelDependency
{
    public function __construct(\stdClass $class)
    {
    }
}