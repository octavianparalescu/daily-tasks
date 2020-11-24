<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Contract;


use DailyTasks\Framework\ADR\Entity\ResolvedRoute;

interface RouteMatcherInterface
{
    public function match(RouteInterface $route, string $path, ?string $verb = null): ?ResolvedRoute;
}