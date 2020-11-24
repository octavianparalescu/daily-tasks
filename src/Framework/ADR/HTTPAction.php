<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR;


use DailyTasks\Framework\ADR\Contract\ActionInterface;
use Symfony\Component\HttpFoundation\Request;

class HTTPAction implements ActionInterface
{
    /**
     * @var Request
     */
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPath(): string
    {
        return $this->request->getPathInfo();
    }

    /**
     * @return Request
     */
    public function getOriginalRequest()
    {
        return $this->request;
    }

    public function getVerb(): ?string
    {
        return $this->getOriginalRequest()
                    ->getMethod();
    }
}