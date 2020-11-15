<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


class Kernel
{
    private string $medium;

    public function __construct(string $medium = 'web')
    {
        $this->medium = $medium;
    }
}