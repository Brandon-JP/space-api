<?php

namespace Vanier\Api\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

abstract class BaseMiddleware
{
    public function prepareResponse(int $status_code, string $error_message, string $error_description) {
        $response_info = [
            "code" => $status_code,
            "message" => $error_message,
            "description" => $error_description
        ];

        $response = new Response();
        $response_info_json = json_encode($response_info);
        $response->getBody()->write($response_info_json);
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }
    public abstract function process(Request $request, RequestHandler $handler);
}
