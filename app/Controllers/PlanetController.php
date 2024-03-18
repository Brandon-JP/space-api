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
    public function handleGetAllPlanets(Request $request, Response $response, array $uri_args): Response
    {
        $planets = $this->planet_model->getAllPlanets();
        $response = $this->makeResponse($response, $planets);
        return $response;
    }

    public function handleGetPlanetById(Request $request, Response $response, array $uri_args) : Response
    {
        $supplied_planet_id = $uri_args["planet_id"];
        $planet = $this->planet_model->getPlanetById($supplied_planet_id);

        $response = $this->makeResponse($response, $planet);
        return $response;
    }
}
