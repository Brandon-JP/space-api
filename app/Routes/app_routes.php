<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\AstronautController;
use Vanier\Api\Controllers\MissionController;
use Vanier\Api\Controllers\PlanetController;
use Vanier\Api\Helpers\DateTimeHelper;

// Import the app instance into this file's scope.
global $app;

// TODO: Add your app's routes here.
//! The callbacks must be implemented in a controller class.
//! The Vanier\Api must be used as namespace prefix. 

//* ROUTE: GET /
$app->get('/', [AboutController::class, 'handleAboutWebService']);

//* ROUTE: GET /hello
$app->get('/hello', function (Request $request, Response $response, $args) {

    $now = DateTimeHelper::getDateAndTime(DateTimeHelper::D_M_Y);
    $response->getBody()->write("Reporting! Hello there! The current time is: " . $now);
    return $response;
});

//* ROUTE: GET /planets
$app->get("/planets", [PlanetController::class, "handleGetAllPlanets"]);
//* ROUTE: GET /planets/{planet_id}
$app->get("/planets/{planet_id}", [PlanetController::class,"handleGetPlanetById"]);
//* ROUTE: GET /planets/{planet_id}/moons
$app->get("/planets/{planet_id}/moons", [PlanetController::class, "handleGetPlanetMoonsById"]);
//* ROUTE: GET /planets/{planet_id}/rovers
$app->get("/planets/{planet_id}/rovers", [PlanetController::class, "handleGetPlanetRoversById"]);

//* ROUTE: GET /missions
$app->get("/missions", [MissionController::class, "handleGetAllMissions"]);
//* ROUTE: GET /missions/{mission_id}
$app->get("/missions/{mission_id}", [MissionController::class,"handleGetMissionById"]);
//* ROUTE: GET /missions/{planet_id}/rockets
$app->get("/missions/{mission_id}/rockets", [MissionController::class, "handleGetMissionRocketsById"]);
//* ROUTE: GET /missions/{planet_id}/rovers
$app->get("/missions/{mission_id}/rovers", [MissionController::class, "handleGetMissionRoversById"]);
//* ROUTE: GET /astronauts
$app->get("/astronauts", [AstronautController::class, "handleGetAllAstronauts"]);
//* ROUTE: GET /astronauts/{astronaut_id}
$app->get("/astronauts/{astronaut_id}", [AstronautController::class, "handleGetAstronautById"]);