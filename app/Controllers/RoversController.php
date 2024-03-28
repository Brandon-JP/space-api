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
        $this->rover_model->setPaginationOptions(
            $request,
            $filters["page"] ?? 1,
            $filters["page_size"] ?? 15
        );
        $rovers["Rovers"] = $this->rover_model->getAllRovers($filters);
        
        return $this->makeResponse($response,$rovers);
    }

    public function handleGetRover(Request $request, Response $response, array $uri_args){
        $rover_id = $uri_args["rover_id"];
        $this->validateIntId($request,$rover_id);
        $rover["Rover"] = $this->rover_model->getRover($rover_id);    
        return $this->makeResponse($response,$rover);
    }
    
    public function handleGetRoverMissions(Request $request, Response $response, array $uri_args){
        $filters = $request->getQueryParams();
        $rover_id = $uri_args["rover_id"];
        $this->validateIntId($request,$rover_id);
        $this->rover_model->setPaginationOptions(
            $request,
            $filters["page"] ?? 1,
            $filters["page_size"] ?? 15
        );  
        $rover["Rover"] = $this->rover_model->getRover($rover_id);
        $rover["Missions"] = $this->rover_model->getRoverMissions($filters,$rover_id);
       
        return $this->makeResponse($response,$rover);
    }

}
