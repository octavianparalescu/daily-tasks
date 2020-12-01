<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait HTTPCorsHandler
{
    public function acceptRequestFromDomainFriends(?array $friends, Request $request, Response $response)
    {
        $origin = $request->headers->get('origin');
        if (is_array($friends) && in_array($origin, $friends)) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
            $response->headers->set('Vary', 'Origin');
        }
    }
}