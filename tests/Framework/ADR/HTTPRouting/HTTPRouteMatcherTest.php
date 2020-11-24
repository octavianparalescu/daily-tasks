<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\HTTPRouting;

use DailyTasks\Framework\ADR\Entity\HTTPRoute;
use DailyTasks\Framework\ADR\Key\HTTPRouteKey;
use DailyTasks\Framework\Http\Verbs;
use PHPUnit\Framework\TestCase;

class HTTPRouteMatcherTest extends TestCase
{
    public function testMatchSimpleRoute()
    {
        $routeMatcher = new HTTPRouteMatcher();
        $routeString = '/test';
        $verb = Verbs::GET;

        $route = new HTTPRoute(new HTTPRouteKey($verb, $routeString), $verb, $routeString, 'class');
        $this->assertIsObject($routeMatcher->match($route, $routeString, $verb));
    }

    public function testDoNotMatchSimpleRoute()
    {
        $routeMatcher = new HTTPRouteMatcher();
        $routeString = '/test';
        $verb = Verbs::GET;

        $route = new HTTPRoute(new HTTPRouteKey($verb, $routeString), $verb, $routeString, 'class');
        $this->assertNull($routeMatcher->match($route, '/something-else', $verb));
    }

    public function testDoNotMatchSimpleRouteDifferentLevels()
    {
        $routeMatcher = new HTTPRouteMatcher();
        $routeString = '/test';
        $verb = Verbs::GET;

        $route = new HTTPRoute(new HTTPRouteKey($verb, $routeString), $verb, $routeString, 'class');
        $this->assertNull($routeMatcher->match($route, $routeString . '/another-level', $verb));
    }

    public function testMatchParameterRoute()
    {
        $routeMatcher = new HTTPRouteMatcher();
        $routeString = '/test/[param1]';
        $verb = Verbs::GET;

        $route = new HTTPRoute(new HTTPRouteKey($verb, $routeString), $verb, $routeString, 'class');
        $resolvedRoute = $routeMatcher->match($route, '/test/test-param', $verb);
        $this->assertIsObject($resolvedRoute);
        $this->assertEquals(
            'test-param',
            $resolvedRoute->getParameterBag()
                          ->get('param1')
        );
    }

    public function testMatchMultipleParametersRoute()
    {
        $routeMatcher = new HTTPRouteMatcher();
        $routeString = '/test/[param1]/[param2]';
        $verb = Verbs::GET;

        $route = new HTTPRoute(new HTTPRouteKey($verb, $routeString), $verb, $routeString, 'class');
        $resolvedRoute = $routeMatcher->match($route, '/test/test-param/test-param2', $verb);
        $this->assertIsObject($resolvedRoute);
        $this->assertEquals(
            'test-param',
            $resolvedRoute->getParameterBag()
                          ->get('param1')
        );
        $this->assertEquals(
            'test-param2',
            $resolvedRoute->getParameterBag()
                          ->get('param2')
        );
    }

    public function testMatchIntertwinedMultipleParametersRoute()
    {
        $routeMatcher = new HTTPRouteMatcher();
        $routeString = '/test/[param1]/test2/[param2]';
        $verb = Verbs::GET;

        $route = new HTTPRoute(new HTTPRouteKey($verb, $routeString), $verb, $routeString, 'class');
        $resolvedRoute = $routeMatcher->match($route, '/test/test-param/test2/test-param3', $verb);
        $this->assertIsObject($resolvedRoute);
        $this->assertEquals(
            'test-param',
            $resolvedRoute->getParameterBag()
                          ->get('param1')
        );
        $this->assertEquals(
            'test-param3',
            $resolvedRoute->getParameterBag()
                          ->get('param2')
        );
    }
}
