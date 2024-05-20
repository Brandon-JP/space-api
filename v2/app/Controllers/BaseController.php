<?php

declare(strict_types=1);

namespace Vanier\Api\Controllers;
use Psr\Http\Message\RequestInterface as Request;
use Slim\Psr7\Response;
use Vanier\Api\Exceptions\HttpBadRequestException;
use Vanier\Api\Helpers\InputsHelper;

abstract class BaseController
{
    protected function makeResponse(Response $response, array $data, int $status_code= 200): Response
    {
        // var_dump($data);
        $json_data = json_encode($data);
        //-- Write JSON data into the response's body.        
        $response->getBody()->write($json_data);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    protected function validateIntId(Request $request, mixed $subject)
    {
        $id_valid = InputsHelper::isInt($subject, 1);
        if(!$id_valid)
        {
            $invalid_id_exception_message = "The supplied ID is in an incorrect format. It must be a number greater or equal to 1.";
            throw new HttpBadRequestException($request, $invalid_id_exception_message);
        }
    }

    


}
