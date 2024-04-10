<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Exceptions\HttpInvalidInputException;
use Vanier\Api\Exceptions\HttpMissingParams;
use Vanier\Api\Helpers\Validator;
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


    public function handleCreateMoons(Request $request, Response $response, array $uri_args): Response
    {
        $moons = $request->getParsedBody();
        $moon_model = new MoonModel();
        foreach ($moons as $key=> $moon) {
            $param_valid = new Validator($moon);
            $param_valid->rule('required', ['moon_name','planet_id','radius','density','magnitude','albedo']);
            if(!$param_valid->validate()) {
                throw new HttpMissingParams($request);
            }
            $moon_model->createMoon($moon);
        }

        $response_data = array(
            "code" => "successful",
            "message" => "The list of moons was successfully created!"

        );

        return $this->makeResponse(
            $response,
            $response_data,
            201
        ); 
    }

    public function handleUpdateMoons(Request $request, Response $response, array $uri_args): Response{

        
        $moons = $request->getParsedBody();
        $moon_model = new MoonModel();
        foreach ($moons as $key=> $moon) {
            

            $param_valid = new Validator($moon);
            $param_valid->rule('required', ['moon_id','moon_name','planet_id','radius','density','magnitude','albedo']);

            if(!$param_valid->validate()) {
                //var_dump($param_valid->errors());
                throw new HttpMissingParams($request);
                
            }
            $moon_id = $moon["moon_id"];
            unset($moon["moon_id"]);
            $moon_model->updateMoon($moon,$moon_id);
        }


        $response_data = array(
            "code" => "successful",
            "message" => "The list of moons was successfully updated!"

        );
        
        return $this->makeResponse(
            $response,
            $response_data,
            201
        );        
    }

    public function handleDeleteMoons(Request $request, Response $response, array $uri_args): Response
    {

        $moons = $request->getParsedBody();
        $moon_model = new MoonModel();
        foreach ($moons as  $moon_id) {
            
            $id_valid = new Validator($moons);
            $id_valid->rule('numeric','moon_id');

            if(!$id_valid->validate()) {
                //var_dump($param_valid->errors());
                throw new HttpMissingParams($request);
            }
            $moon_model->deleteMoon($moon_id);
        }

        $response_data = array(
            "code" => "successful",
            "message" => "The list of moons was successfully deleted!"

        );
        
        return $this->makeResponse(
            $response,
            $response_data,
            200
        ); 
    }

}
