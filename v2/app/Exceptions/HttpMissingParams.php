<?php

namespace Vanier\Api\Exceptions;
use Slim\Exception\HttpSpecializedException;

class HttpMissingParams extends HttpSpecializedException
{
      /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Unprocessable Entity.';

    protected string $title = '422 Unprocessable Entity';
    protected string $description = 'The server was unable to process the request because it contains invalid data.';
}
