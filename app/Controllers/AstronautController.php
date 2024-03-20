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
        $astronauts = $this->astronaut_model->getAllAstronauts();
        $response = $this->makeResponse($response, $astronauts);
        return $response;
    }
}
