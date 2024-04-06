<?php

declare(strict_types=1);

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;
use Vanier\Api\Models\MeteoriteModel;

class MeteoriteController extends BaseController
{
    public MeteoriteModel $meteorite_model;

    public function __construct()
    {
        $this->meteorite_model = new MeteoriteModel();
    }

    public function handleGetAllMeteorites(Request $request, Response $response, array $uri_args): Response
    {
        $meteorites_filters = $request->getQueryParams();
        $this->meteorite_model->setPaginationOptions(
            $request,
            $meteorites_filters["page"] ?? 1,
            $meteorites_filters["page_size"] ?? 15
        );

        $meteorites = $this->meteorite_model->getAllMeteorites($meteorites_filters);
        $response = $this->makeResponse($response, $meteorites);
        return $response;
    }

    public function handleCreateMeteorites(Request $request, Response $response, array $uri_args)
    {
        $meteorite_parsed_body = $request->getParsedBody();

        foreach($meteorite_parsed_body as $meteorite)
        {
            $this->meteorite_model->createMeteorite($request, $meteorite);
        }

        $response_data = [
            "code" => "Created",
            "message" => "The list of meteorites was created!"
        ];

        $response = $this->makeResponse(
            $response,
            $response_data,
            201
        );

        return $response;
    }
    
    public function handleGetMeteoriteById(Request $request, Response $response, array $uri_args): Response
    {
        $supplied_meteorite_id = $uri_args["meteorite_id"];
        $this->validateIntId($request, $supplied_meteorite_id);

        $meteorite = $this->meteorite_model->getMeteoriteById($supplied_meteorite_id);
        $response = $this->makeResponse($response, $meteorite);
        return $response;
    }
}
