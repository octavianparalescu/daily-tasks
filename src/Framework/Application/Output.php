<?php
declare(strict_types=1);

namespace DailyTasks\Framework\Application;


use Symfony\Component\HttpFoundation\Response;

class Output
{
    public static function outputHTTP(Response $response)
    {
        $response->send();
    }
}