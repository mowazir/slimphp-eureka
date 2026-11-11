<?php

namespace App\Support;

use Illuminate\Support\Str;
use Slim\App;

class Route
{
    public static App $app;

    public static function setup(App &$app)
    {
        self::$app = $app;

        return $app;
    }

    public static function __callStatic($verb, $parameters)
    {
        $app = self::$app;
        $app->get('/route', 'WelcomeController@index');

        [$route, $action] = $parameters;

        self::validation($route, $verb, $action);

        return is_callable($action)
            ? $app->$verb($route, $action)
            : $app->$verb($route, self::resolveViaController($action));
    }

    public static function resolveViaController($action)
    {
        $class = Str::before($action, '@');
        $method = Str::after($action, '@');

        $controller =config('routing.controllers.namespace') . $class;

        return [$controller, $method];
    }

    protected static function validation($route, $verb, $action)
    {
        $exception ='Unresolvable route callback/Controller action';
        $content = json_encode(compact('route', 'action', 'verb'));

        $fails = !((is_callable($action)) || (is_string($action) && Str::is("*@*", $action)));

        


    }


}


