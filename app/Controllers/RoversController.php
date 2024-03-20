<?php

namespace Vanier\Api\Controllers;
use Vanier\Api\Models\RoversModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class RoversController extends BaseController
{
    public $rover_model = null;

    function __construct(){
        $this->rover_model = new RoversModel();
    }

    public function handleGetAllRovers(Request $request, Response $response, array $uri_args){

        $filters = $request->getQueryParams();
        $rovers["Rovers"] = $this->rover_model->getAllRovers($filters);
        return $this->makeResponse($response,$rovers);
    }

    public function handleGetRover(Request $request, Response $response, array $uri_args){
        $rover_id = $uri_args["rover_id"];
        $rover["Rover"] = $this->rover_model->getRover($rover_id);    
        return $this->makeResponse($response,$rover);
    }

}
