<?php
declare(strict_types=1);

use DailyTasks\Framework\ADR\Action\HTTPActionFactory;
use DailyTasks\Framework\ADR\Contract\ActionFactoryInterface;
use DailyTasks\Framework\Http\Verbs;
use DailyTasks\Main\Action\IndexAction;

return [
    'di_static' => [
        ActionFactoryInterface::class => HTTPActionFactory::class,
    ],
    'di_factory' => [],
    'web_routes' => [
        '/' => [
            Verbs::GET => IndexAction::class,
        ],
    ],
    'cli_routes' => [
        'test' => IndexAction::class,
    ],
];