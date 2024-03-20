<?php

namespace Vanier\Api\Controllers;
use Vanier\Api\Models\AstronautModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class AstronautController extends BaseController
{
    public AstronautModel $astronaut_model;
    public function __construct()
    {
        $this->astronaut_model = new AstronautModel();
    }
    
    public function handleGetAllAstronauts(Request $request, Response $response, array $uri_args) : Response
    {
        $astronauts_filters = $request->getQueryParams();
        $astronauts = $this->astronaut_model->getAllAstronauts($astronauts_filters);
        $response = $this->makeResponse($response, $astronauts);
        return $response;
    }

    public function handleGetAstronautById(Request $request, Response $response, array $uri_args) : Response
    {
        $supplied_astronaut_id = $uri_args["astronaut_id"];
        $astronaut = $this->astronaut_model->getAstronautById($supplied_astronaut_id);

        $response = $this->makeResponse($response, $astronaut);
        return $response;
    }
}
