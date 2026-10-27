<?php

use DI\Container;
use DI\Bridge\Slim\Bridge as SlimAppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container;

$settings = require_once __DIR__ . '/../app/settings.php';
$settings($container);

$app = SlimAppFactory::create($container);

$middleware = require_once __DIR__ . '/../app/middleware.php';
$middleware($app);

//$app->setBasePath("/slimphp-eureka");

$routes = require_once __DIR__ . '/../app/routes.php';
$routes($app);

$app->run();

