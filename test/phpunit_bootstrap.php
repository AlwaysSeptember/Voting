<?php

use Auryn\Injector;

require_once(__DIR__.'/../vendor/autoload.php');
require_once __DIR__ . '/../injectionParams/cliTest.php';
require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/factories.php';
require_once __DIR__ . '/../src/fakes.php';
require_once __DIR__ . '/../src/db_functions.php';

/**
 * @param array $testAliases
 * @return \Auryn\Injector
 */
function createInjector($testDoubles = [], $shareDoubles = [])
{
    $injectionParams = injectionParams($testDoubles);

    $injector = new \Auryn\Injector();
    $injectionParams->addToInjector($injector);

    foreach ($shareDoubles as $shareDouble) {
        $injector->share($shareDouble);
    }

    $injector->share($injector); //Yolo ServiceLocator
    return $injector;
}
