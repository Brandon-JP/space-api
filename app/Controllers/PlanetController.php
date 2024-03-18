<?php
declare(strict_types=1);
namespace Vanier\Api\Controllers;



use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\PlanetModel;

class PlanetController extends BaseController
{
    public PlanetModel $planet_model;
    public function __construct()
    {
        $this->planet_model = new PlanetModel();
    }
    public function handleGetPlanets(Request $request, Response $response, array $uri_args): Response
    {
        $planets = $this->planet_model->getPlanets();
        $response = $this->makeResponse($response, $planets);
        return $response;
    }
}
