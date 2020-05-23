<?php

use ASVoting\InjectionParams;

if (function_exists('injectionParams') == false) {

    function injectionParams() : InjectionParams
    {
        // These classes will only be created once by the injector.
        $shares = [
            \Doctrine\ORM\EntityManager::class,
//            \Airbrake\Notifier::class
        ];

        // Alias interfaces (or classes) to the actual types that should be used
        // where they are required.
        $aliases = [
//            \ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource::class =>
//                \ASVoting\Repo\ProposedMotionExternalSource\FakeProposedMotionExternalSource::class,

            \ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource::class =>
                \ASVoting\Repo\ProposedMotionExternalSource\CurlProposedMotionExternalSource::class,

//            \ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage::class =>
//                \ASVoting\Repo\ProposedMotionStorage\FakeProposedMotionStorage::class,

            \ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage::class =>
                \ASVoting\Repo\ProposedMotionStorage\RedisProposedMotionStorage::class
        ];

        // Delegate the creation of types to callables.
        $delegates = [
            \PDO::class => 'createPDO',
            \Redis::class => 'createRedis',
        ];

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
}


return injectionParams();
