<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\MoonModel;
class MoonController extends BaseController
{
    private $moon_model;

    public function __construct(){
        $this->moon_model = new MoonModel();
    }

    public function handleGetAllMoons(Request $request, Response $response, array $uri_args){
        $filters = $request->getQueryParams();
        $this->moon_model->setPaginationOptions(
            $request,
            $filters["page"] ?? 1,
            $filters["page_size"] ?? 15
        );
        $moons = $this->moon_model->getAllMoons($filters);

        return $this->makeResponse($response,$moons);
    }


    public function handleGetMoon(Request $request, Response $response, array $uri_args){
        $moon_id = $uri_args["moon_id"];
        $this->validateIntId($request,$moon_id);
        $moon = $this->moon_model->getMoon($moon_id);

        return $this->makeResponse($response,$moon);
    
    }

    public function handleGetMoonRovers(Request $request, Response $response, array $uri_args){
        $filters = $request->getQueryParams();
        $this->moon_model->setPaginationOptions(
            $request,
            $filters["page"] ?? 1,
            $filters["page_size"] ?? 15
        );
        $moon_id = $uri_args["moon_id"];
        $this->validateIntId($request,$moon_id);
        $moon["moon"] = $this->moon_model->getMoon($moon_id);
        $moon["rover(s)"] = $this->moon_model->getMoonRovers($moon_id, $filters);
        return $this->makeResponse($response,$moon);
    }

}
