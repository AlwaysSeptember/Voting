<?php

declare(strict_types = 1);

error_reporting(E_ALL);

$isStaffApiEndpoint = false;

require __DIR__ . "/../vendor/autoload.php";

require __DIR__ . '/factories.php';
require __DIR__ . '/exception_mappers_http.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/slim_functions.php';
require __DIR__ . '/twig_functions.php';
require __DIR__ . '/db_functions.php';

use SlimAuryn\ExceptionMiddleware;
use SlimAuryn\SlimAurynInvokerFactory;
use SlimAuryn\Routes;

set_error_handler('saneErrorHandler');

$injector = new Auryn\Injector();
$injectionParams = injectionParams();
$injectionParams->addToInjector($injector);
$injector->share($injector);

try {
    $container = new \Slim\Container;
    $injector->share($container);

    $container['foundHandler'] = $injector->make(SlimAurynInvokerFactory::class);

    $app = $injector->make(\Slim\App::class);
    $routes = $injector->make(Routes::class);
    $routes->setupRoutes($app);

    $app->run();
}
catch (\Exception $exception) {
    echo "Exception in code and Slim error handler failed also: <br/>";
    var_dump(get_class($exception));
    showException($exception);
}
