<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI\TestClasses;


use Exception;

class TestClassExceptionInConstructor
{
    public function __construct()
    {
        throw new Exception();
    }
}