<?php
declare(strict_types=1);

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\MissionModel;
class MissionController extends BaseController
{
    public MissionModel $mission_model;

    public function __construct() {
        $this->mission_model = new MissionModel();
    }
    public function handleGetAllMissions(Request $request, Response $response, array $uri_args) : Response
    {
        $missions_filters = $request->getQueryParams();
        $missions = $this->mission_model->getAllMissions($missions_filters);
        $response = $this->makeResponse($response, $missions);
        return $response;
    }

    public function handleGetMissionById(Request $request, Response $response, array $uri_args) : Response 
    {
        $supplied_mission_id = $uri_args["mission_id"];
        $mission = $this->mission_model->getMissionById($supplied_mission_id);
        $response = $this->makeResponse($response, $mission);
        return $response;
    }

    public function handleGetMissionRocketsById(Request $request, Response $response, array $uri_args) : Response
    {
        $supplied_mission_id = $uri_args["mission_id"];
        $mission_rockets_filters = $request->getQueryParams();
        $mission_rockets = $this->mission_model->getMissionRocketsById($supplied_mission_id, $mission_rockets_filters);

        $response = $this->makeResponse($response, $mission_rockets);

        return $response;
    }

    public function handleGetMissionRoversById(Request $request, Response $response, array $uri_args) : Response
    {
        $supplied_mission_id = $uri_args["mission_id"];
        $mission_rovers_filters = $request->getQueryParams();
        $mission_rovers = $this->mission_model->getMissionRoversById($supplied_mission_id, $mission_rovers_filters);

        $response = $this->makeResponse($response, $mission_rovers);

        return $response;
    }
}
