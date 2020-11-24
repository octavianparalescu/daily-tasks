<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Entity;


use DailyTasks\Framework\ADR\Contract\RouteInterface;
use DailyTasks\Framework\Data\Immutable;
use Symfony\Component\HttpFoundation\ParameterBag;

class ResolvedRoute implements Immutable
{
    /**
     * @var RouteInterface
     */
    private RouteInterface $matchedRoute;
    /**
     * @var ParameterBag
     */
    private ParameterBag $parameterBag;

    /**
     * ResolvedRoute constructor.
     *
     * @param RouteInterface $matchedRoute
     * @param ParameterBag   $parameterBag
     */
    public function __construct(RouteInterface $matchedRoute, ParameterBag $parameterBag)
    {
        $this->matchedRoute = $matchedRoute;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @return RouteInterface
     */
    public function getMatchedRoute(): RouteInterface
    {
        return $this->matchedRoute;
    }

    /**
     * @return ParameterBag
     */
    public function getParameterBag(): ParameterBag
    {
        return $this->parameterBag;
    }
}