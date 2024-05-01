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
        $planets_filters = $request->getQueryParams();
        $this->planet_model->setPaginationOptions(
            $request,
            $planets_filters["page"] ?? 1,
            $planets_filters["page_size"] ?? 15
        );
        
        $planets = $this->planet_model->getAllPlanets($planets_filters);
        $amiibos_data = $this->planet_model->getAmiibosData();
        $planets["amiibos"] = $this->planet_model->parseAmiibos($amiibos_data);
        $response = $this->makeResponse($response, $planets);
        return $response;
    }

    
    public function handleGetPlanetById(Request $request, Response $response, array $uri_args) : Response
    {
        $supplied_planet_id = $uri_args["planet_id"];
        $this->validateIntId($request, $supplied_planet_id);

        $planet = $this->planet_model->getPlanetById($supplied_planet_id);

        $response = $this->makeResponse($response, $planet);
        return $response;
    }

    public function handleGetPlanetMoonsById(Request $request, Response $response, array $uri_args) : Response
    {
        $supplied_planet_id = $uri_args["planet_id"];
        $this->validateIntId($request, $supplied_planet_id);

        $planet_moons_filters = $request->getQueryParams();
        
        $this->planet_model->setPaginationOptions(
            $request,
            $planet_moons_filters["page"] ?? 1,
            $planet_moons_filters["page_size"] ?? 15
        );
        $planet_moons = $this->planet_model->getPlanetMoonsById($supplied_planet_id, $planet_moons_filters);

        $response = $this->makeResponse($response, $planet_moons);
        return $response;
    }

    public function handleGetPlanetRoversById(Request $request, Response $response, array $uri_args) : Response
    {
        $supplied_planet_id = $uri_args["planet_id"];
        $this->validateIntId($request, $supplied_planet_id);
        
        $planet_rovers_filters = $request->getQueryParams();
        $this->planet_model->setPaginationOptions(
            $request,
            $planet_rovers_filters["page"] ?? 1,
            $planet_rovers_filters["page_size"] ?? 15
        );
        $planet_rovers = $this->planet_model->getPlanetRoversById($supplied_planet_id, $planet_rovers_filters);
        $response = $this->makeResponse($response, $planet_rovers);
        
        return $response;
    }
}
