<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\RocketModel;

class RocketController extends BaseController
{
    public RocketModel $rocket_model;

    public function __construct()
    {
        $this->rocket_model = new RocketModel();
    }

    public function handleGetAllRockets(Request $request, Response $response, array $uri_args): Response
    {
        $rocket_filters = $request->getQueryParams();
        $this->rocket_model->setPaginationOptions(
            $request,
            $rocket_filters["page"] ?? 1,
            $rocket_filters["page_size"] ?? 15
        );

        $rockets = $this->rocket_model->getAllRockets($rocket_filters);
        $response = $this->makeResponse($response, $rockets);
        return $response;
    }

    public function handleGetRocketById(Request $request, Response $response, array $uri_args): Response
    {
        $supplied_rocket_id = $uri_args["rocket_id"];
        $this->validateIntId($request, $supplied_rocket_id);

        $rocket = $this->rocket_model->getRocketById($supplied_rocket_id);

        $response = $this->makeResponse($response, $rocket);
        return $response;
    }

    public function handleGetRocketByIdMission(Request $request, Response $response, array $uri_args): Response
    {
        $supplied_rocket_id = $uri_args["rocket_id"];
        $this->validateIntId($request, $supplied_rocket_id);
        $rocket_missions_filters = $request->getQueryParams();
        $rocket_missions = $this->rocket_model->getRocketMissionsById($supplied_rocket_id, $rocket_missions_filters);

        $response = $this->makeResponse($response, $rocket_missions);
        return $response;
    }

}
