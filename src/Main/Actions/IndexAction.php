<?php
declare(strict_types=1);

namespace DailyTasks\Main\Actions;


use DailyTasks\Framework\ADR\Contract\ActionHandlerInterface;
use DailyTasks\Framework\ADR\Contract\ActionInterface;
use DailyTasks\Framework\ADR\HTTPAction;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

class IndexAction implements ActionHandlerInterface
{
    /**
     * @param ActionInterface|HTTPAction $action
     * @param ParameterBag               $parameters
     *
     * @return Response
     */
    public function handle(ActionInterface $action, ParameterBag $parameters): Response
    {
        $response = new Response();
        $response->setContent('index action');

        return $response;
    }
}