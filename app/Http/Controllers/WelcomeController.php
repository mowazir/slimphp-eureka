<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class WelcomeController
{
    public function index (Request $request, Response $response)
    {
        $response->getBody()->write('Welcome World');
        return $response;
    }

    public function show( $response, $name)
    {
        $response->getBody()->write('Hy '. "{$name}");
        return $response;

    }
    
}
