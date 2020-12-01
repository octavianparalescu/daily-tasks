<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Action;


use DailyTasks\Framework\ADR\Contract\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HTTPAction implements ActionInterface
{
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var Response
     */
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function getPath(): string
    {
        return $this->request->getPathInfo();
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function getVerb(): ?string
    {
        return $this->getRequest()
                    ->getMethod();
    }

    public function getResponse()
    {
        return $this->response;
    }
}