<?php
declare(strict_types=1);

use DailyTasks\Framework\ADR\Contract\ActionFactoryInterface;
use DailyTasks\Framework\ADR\HTTPActionFactory;

return [
    'di' => [
        'static' => [
            ActionFactoryInterface::class => HTTPActionFactory::class,
        ],
        'factory' => [],
    ],
];