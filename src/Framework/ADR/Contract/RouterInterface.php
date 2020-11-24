<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Contract;


use DailyTasks\Framework\ADR\Entity\ResolvedRoute;
use DailyTasks\Framework\Application\Medium;

interface RouterInterface
{
    /**
     * Should return the domain folder from a path or null if not found
     *
     * @param Medium      $medium
     * @param string      $path
     * @param string|null $verb
     *
     * @return ResolvedRoute|null
     */
    public function mapPathToRoute(Medium $medium, string $path, ?string $verb = null): ?ResolvedRoute;
}