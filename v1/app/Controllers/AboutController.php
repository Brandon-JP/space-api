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
                'Description' => 'This api provides a service which offers space information,N this service supports GET,PUT,POST and DELETE',
                'Version' => '1.0.0'
            ],
            'Resources' => [
                'rockets' => 'space-api/rockets',
                'moons' => 'space-api/moons',
                'planets' => 'space-api/planets',
                'meteorites' => 'space-api/meteorites',
                'rovers' => 'space-api/rovers',
                'missions' => 'space-api/missions',
                'astronauts' => 'space-api/astronauts',

            ]
        );
        return $this->makeResponse($response, $data);
    }
}
