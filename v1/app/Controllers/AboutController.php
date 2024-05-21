<?php

declare(strict_types=1);

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    public function handleAboutWebService(Request $request, Response $response, array $uri_args): Response
    {
        $data = array(
            'About' => [
                'Authors' => 'This API was created by Logan Luo and Brandon Pannunzio',
                'Description' => 'This api shows space information'
            ],
            'Resources' => [
                'rockets' => 'space-api/v1/rockets',
                'moons' => 'space-api/v1/moons',
                'planets' => 'space-api/v1/planets',
                'meteorites' => 'space-api/v1/meteorites',
                'rovers' => 'space-api/v1/rovers',
                'missions' => 'space-api/v1/missions',
                'astronauts' => 'space-api/v1/astronauts',

            ]
        );
        return $this->makeResponse($response, $data);
    }
}
