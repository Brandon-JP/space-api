<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\AccountsController;
use Vanier\Api\Controllers\AstronautController;
use Vanier\Api\Controllers\MissionController;
use Vanier\Api\Controllers\MoonController;
use Vanier\Api\Controllers\PlanetController;
use Vanier\Api\Controllers\RoversController;
use Vanier\Api\Helpers\DateTimeHelper;
use Vanier\Api\Controllers\RocketController;
use Vanier\Api\Controllers\MeteoriteController;

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

//* ROUTE: GET /rovers
$app->get("/rovers", [RoversController::class, "handleGetAllRovers"]);
//* ROUTE: GET /rovers/{rover_id}
$app->get("/rovers/{rover_id}", [RoversController::class, "handleGetRover"]);
//* ROUTE: GET /rovers/{rover_id}/missions
$app->get("/rovers/{rover_id}/missions", [RoversController::class, "handleGetRoverMissions"]);

//* ROUTE: GET /moons
$app->get("/moons", [MoonController::class, "handleGetAllMoons"]);
//* ROUTE: GET /moons/{moon_id}
$app->get("/moons/{moon_id}", [MoonController::class, "handleGetMoon"]);
//* ROUTE: GET /moons/{moon_id}/rovers
$app->get("/moons/{moon_id}/rovers", [MoonController::class, "handleGetMoonRovers"]);

//*ROUTE POST /moons
$app->post('/moons', [MoonController::class, 'handleCreateMoons']);

//* ROUTE PUT/ moons
$app->put('/moons', [MoonController::class,'handleUpdateMoons']);

//* ROUTE DELETE /moons
$app->delete('/moons', [MoonController::class, 'handleDeleteMoons']);

//* ROUTE: GET /astronauts
$app->get("/astronauts", [AstronautController::class, "handleGetAllAstronauts"]);
//* ROUTE: GET /astronauts/{astronaut_id}
$app->get("/astronauts/{astronaut_id}", [AstronautController::class, "handleGetAstronautById"]);

//* ROUTE: GET /meteorites
$app->get("/meteorites", [MeteoriteController::class, "handleGetAllMeteorites"]);
//* ROUTE: GET /meteorites/{meteorite_id}
$app->get("/meteorites/{meteorite_id}", [MeteoriteController::class, "handleGetAllMeteoriteByID"]);
//* ROUTE: POST /meteorites
$app->post("/meteorites", [MeteoriteController::class, "handleCreateMeteorites"]);
//* ROUTE: PUT /meteorites
$app->put("/meteorites", [MeteoriteController::class, "handleUpdateMeteorites"]);
//* ROUTE: DELETE /meteorites
$app->delete("/meteorites", [MeteoriteController::class, "handleDeleteMeteorites"]);
//* ROUTE: GET /rockets
$app->get("/rockets", [RocketController::class, "handleGetAllRockets"]);
//* ROUTE: GET /rockets/{rocket_id}
$app->get("/rockets/{rocket_id}", [RocketController::class, "handleGetRocketById"]);
//* ROUTE: GET /rockets/{rocket_id}/missions
$app->get("/rockets/{rocket_id}/missions", [RocketController::class, "handleGetRocketByIdMission"]);

//* ROUTE: POST /account
$app->post("/account", [AccountsController::class, "handleCreateAccount"]);

//* ROUTE: POST /token
$app->post("/token", [AccountsController::class, "handleGenerateToken"]);
