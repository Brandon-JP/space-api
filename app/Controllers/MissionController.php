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
    public function handleGetAllMissions(Request $request, Response $response, array $uri_args)
    {
        $missions = $this->mission_model->getAllMissions();
        $response = $this->makeResponse($response, $missions);
        return $response;
    }
}
