<?php
declare(strict_types=1);

use DailyTasks\Framework\Http\Verbs;
use DailyTasks\Task\Action\CreateTaskAction;
use DailyTasks\Task\Action\DeleteTaskAction;
use DailyTasks\Task\Action\FetchAllTasksAction;
use DailyTasks\Task\Action\FetchTaskAction;
use DailyTasks\Task\Action\FetchTasksByDueDateAction;
use DailyTasks\Task\Action\PutTaskAction;
use DailyTasks\Task\Action\TaskOptionsAction;

return [
    'db' => [
        'dbname' => envConfig('task', 'DB_NAME', 'task'),
        'user' => envConfig('task', 'DB_USER', 'task'),
        'password' => envConfig('task', 'DB_PASSWORD', 'task'),
        'host' => envConfig('task', 'DB_HOST', 'persistent_db_dev'),
        'driver' => envConfig('task', 'DB_DRIVER', 'mysqli'),
    ],
    'web_routes' => [
        '/task/findByDate' => [
            Verbs::GET => FetchTasksByDueDateAction::class,
        ],
        '/task/[id]' => [
            Verbs::GET => FetchTaskAction::class,
            Verbs::DELETE => DeleteTaskAction::class,
            Verbs::OPTIONS => TaskOptionsAction::class,
        ],
        '/task' => [
            Verbs::POST => CreateTaskAction::class,
            Verbs::GET => FetchAllTasksAction::class,
            Verbs::OPTIONS => TaskOptionsAction::class,
            Verbs::PUT => PutTaskAction::class,
        ],
    ],
    'domain_friends' => array_merge(
        [
            'http://localhost:8082', // swagger dev
        ],
        domainFriendsFromEnvConfig('task')
    ),
];