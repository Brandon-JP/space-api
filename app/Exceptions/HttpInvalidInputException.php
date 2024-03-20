<?php

namespace Vanier\Api\Exceptions;
use Slim\Exception\HttpSpecializedException;

class HttpInvalidInputException extends HttpSpecializedException
{
      /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Bad request.';

    protected string $title = '400 Bad Request';
    protected string $description = 'The request could not be understood due to incorrect parameters or missing parameters.';
}
