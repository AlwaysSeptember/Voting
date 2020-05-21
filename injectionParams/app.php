<?php

use ASVoting\InjectionParams;

function injectionParams()
{
    // These classes will only be created once by the injector.
    $shares = [
        \Redis::class,
//        \Twig_Environment::class,
        \Auryn\Injector::class,
        \Doctrine\ORM\EntityManager::class,
        \Slim\Container::class,
        \Slim\App::class,
//        \ASVoting\CSPViolation\RedisCSPViolationStorage::class,
//        \ASVoting\Service\RequestNonce::class,
    ];

    // Alias interfaces (or classes) to the actual types that should be used
    // where they are required.
    $aliases = [
//        ASVoting\Repo\ProjectRepo\ProjectRepo::class =>
//        ASVoting\Repo\ProjectRepo\DoctrineProjectRepo::class,
        \VarMap\VarMap::class => \VarMap\Psr7VarMap::class,
//        \ASVoting\Repo\SkuPriceRepo\SkuPriceRepo::class =>
//        \ASVoting\Repo\SkuPriceRepo\DatabaseSkuPriceRepo::class,
    ];

    // Delegate the creation of types to callables.
    $delegates = [
        \Psr\Log\LoggerInterface::class => 'createLogger',
        \PDO::class => 'createPDO',
        \Redis::class => '\createRedis',
        Doctrine\ORM\EntityManager::class => 'createDoctrineEntityManager',

        \SlimAuryn\Routes::class => 'createRoutesForApp',
        \SlimAuryn\ExceptionMiddleware::class => 'createExceptionMiddlewareForApp',
        \SlimAuryn\SlimAurynInvokerFactory::class => 'createSlimAurynInvokerFactory',

        \Slim\Container::class => 'createSlimContainer',
        \Slim\App::class => 'createSlimAppForApp',

        \ASVoting\AppErrorHandler\AppErrorHandler::class => 'createHtmlAppErrorHandler',
    ];

//    if (getConfig(['example', 'direct_sending_no_queue'], false) === true) {
//    }

    // Define some params that can be injected purely by name.
    $params = [];

    $prepares = [
    ];

    $defines = [];

    $injectionParams = new InjectionParams(
        $shares,
        $aliases,
        $delegates,
        $params,
        $prepares,
        $defines
    );

    return $injectionParams;
}
