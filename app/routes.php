<?php

use Slim\App;
use Jenssegers\Blade\Blade;
use Illuminate\Container\Container;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Filesystem\Filesystem;
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

    function view(Response $response, $template, $with = []){
        $cache = __DIR__ . '/../cache';
        $views = __DIR__ . '/../resources/views';

        // 2. Initialize and force override the global container instance with our wrapper
        $container = new SlimBladeContainer();
        Container::setInstance($container);

        // 3. Bind 'files' to handle filesystem operations
        if (!$container->bound('files')) {
            $container->singleton('files', function () {
                return new Filesystem();
            });
        }

        // 4. Bind 'blade.compiler' to handle compiling templates
        if (!$container->bound('blade.compiler')) {
            $container->singleton('blade.compiler', function () use ($container, $cache) {
                return new BladeCompiler($container->make('files'), $cache);
            });
        }

        // 5. Safely initialize Blade and render
        $blade = new Blade($views, $cache);
        $html = $blade->make($template, $with)->render();
        
        $response->getBody()->write($html);

        return $response;
    }

    $app->get('/home', function(Request $request, Response $response, $parameters) {
        $name = "Code Clean Arena";

        return view($response, 'auth.home', compact('name'));
    });

    $app->get('/', function(Request $request, Response $response, $parameters) {
        $response->getBody()->write('Hello World');
        return $response;
    });

};
