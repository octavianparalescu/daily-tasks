<?php
declare(strict_types=1);

use DailyTasks\Framework\Application\Kernel;

chdir(dirname(__DIR__));
define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/vendor/autoload.php';

$kernel = new Kernel();
$kernel->start();