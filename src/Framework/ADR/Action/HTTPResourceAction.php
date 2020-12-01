<?php
declare(strict_types=1);

namespace DailyTasks\Framework\ADR\Action;


use DailyTasks\Framework\Data\Validator\Map\ValidationErrorMap;
use DailyTasks\Framework\Data\Validator\Outputter\ValidationErrorMapOutputter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

trait HTTPResourceAction
{
    use ValidationErrorMapOutputter;

    public function returnFetchResultToHTTP(Response $response, ?array $data)
    {
        if ($data) {
            $response->setStatusCode(200);
            $response->setContent(json_encode($data));
            $response->headers->set('content-type', 'application/json');
        } else {
            $this->returnNonExistingEntity($response);
        }
    }

    public function returnMultipleFetchResultToHTTP(Response $response, ?array $data)
    {
        $response->setStatusCode(200);
        $response->setContent(json_encode($data));
        $response->headers->set('content-type', 'application/json');
    }

    public function returnDeleteToHTTP(Response $response, bool $result)
    {
        if ($result) {
            $response->setStatusCode(200);
        } else {
            $this->returnNonExistingEntity($response);
        }
    }

    public function returnServerErrorToHTTP(Response $response, ?Throwable $throwable = null)
    {
        $response->setStatusCode(500);
        if (!env()->isProd() && $throwable) {
            $response->setContent(
                'Exception: ' . $throwable->getMessage() . PHP_EOL . 'Stack trace: ' . $throwable->getTraceAsString()
            );
        }
    }

    public function returnValidationErrorsResultToHTTP(Response $response, ValidationErrorMap $errorMap)
    {
        $response->setStatusCode(405);
        $response->setContent($this->outputValidationErrorsToJson($errorMap));
        $response->headers->set('content-type', 'application/json');
    }

    /**
     * @param Response $response
     */
    private function returnNonExistingEntity(Response $response): void
    {
        $response->setStatusCode(404);
    }
}