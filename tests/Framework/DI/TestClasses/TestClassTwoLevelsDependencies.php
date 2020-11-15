<?php
declare(strict_types=1);

namespace DailyTasks\Framework\DI\TestClasses;


use stdClass;

class TestClassTwoLevelsDependencies
{
    /**
     * @var TestClassOneLevelDependency
     */
    public TestClassOneLevelDependency $classOneLevelDependency;

    /** @noinspection PhpUnusedParameterInspection */
    public function __construct(TestClassOneLevelDependency $classOneLevelDependency, stdClass $class)
    {
        $this->classOneLevelDependency = $classOneLevelDependency;
    }
}