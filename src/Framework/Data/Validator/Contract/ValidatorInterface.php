<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Data\Validator\Contract;


interface ValidatorInterface
{
    public function getRules(): array;
}