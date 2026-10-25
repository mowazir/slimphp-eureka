<?php

use Jenssegers\Blade\Blade;
use Illuminate\Container\Container;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Filesystem\Filesystem;
use Psr\Http\Message\ResponseInterface as Response;

if(!function_exists('view'))
    {
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
    }


