<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI\TestClasses;


class TestClassCircular1
{
    public function __construct(TestClassCircular2 $circular2){}
}