<?php
declare(strict_types=1);

use DailyTasks\Framework\Http\Verbs;
use DailyTasks\Main\Actions\IndexAction;

return [
    'domain_main' => [
        'web_routes' => [
            '/' => [
                Verbs::GET => IndexAction::class,
            ],
        ],
        'cli_routes' => [
            'test' => IndexAction::class,
        ],
    ],
];