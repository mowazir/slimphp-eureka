<?php

use DI\Container;
use DI\Bridge\Slim\Bridge as SlimAppFactory;

$app = SlimAppFactory::create(new Container);

$middleware = require_once __DIR__ . '/../app/middleware.php';
$middleware($app);

//$app->setBasePath("/slimphp-eureka");

$routes = require_once __DIR__ . '/../app/routes.php';
$routes($app);

return $app;

