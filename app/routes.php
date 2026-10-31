<?php

use Slim\App;

use Illuminate\Container\Container;
use App\Http\Controllers\WelcomeController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// 1. Define a custom container class that provides the missing terminating method
if (!class_exists('SlimBladeContainer')) {
    class SlimBladeContainer extends Container {
        public function terminating($callback) {
            // Dummy method to satisfy Laravel's ViewServiceProvider lifecycle requirement
            return $this;
        }
    }
}

return function(App $app) {

    $app->get('/home/{name}', function(Request $request, Response $response) {
        $name = "Code Clean Arena";

        return view($response, 'auth.home', compact('name'));
    });

    $app->get('/', [WelcomeController::class, 'index']);
    $app->get('/{name}', [WelcomeController::class, 'show']);
    

};
