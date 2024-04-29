<?php
declare(strict_types=1);
namespace Vanier\Api\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;

class ContentNegotiationMiddleware extends BaseMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler) : ResponseInterface {
        $accept_header = $request->getHeaderLine("Accept");
        if(!str_contains($accept_header, "application/json")
            && !str_contains($accept_header, "*/*")
            && !str_contains($accept_header, "application/*")
        ) {
            $response = $this->prepareResponse(
                406,
                "Unsupported Resource Representation",
                "The requested resource representation is a representation that cannot be provided."
            );
            return $response;
        }
        else {
            $response = $handler->handle($request);
            return $response;
        }
    }
}
