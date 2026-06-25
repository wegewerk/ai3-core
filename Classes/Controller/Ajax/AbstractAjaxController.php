<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Controller\Ajax;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractAjaxController
{
    public function __construct(
        protected LoggerInterface $logger
    ) {}

    protected function createJsonSuccessResponse(
        ResponseInterface $response,
        array $data
    ): ResponseInterface {
        $response->getBody()->write(json_encode(
            array_merge(['success' => true], $data),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
        ));

        return $response;
    }
    protected function createJsonErrorResponse(
        ResponseInterface $response,
        array $data
    ): ResponseInterface {
        $response = $response->withStatus(500);
        $response->getBody()->write(json_encode(
            array_merge(['success' => false], $data),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
        ));

        return $response;
    }
}
