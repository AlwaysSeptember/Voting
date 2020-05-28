<?php

use AurynConfig\InjectionParams;

//if (function_exists('injectionParams') == false) {

function injectionParams($testDoubles = []) {
    // These classes will only be created once by the injector.
    $shares = [
        \Doctrine\ORM\EntityManager::class
    ];

    // Alias interfaces (or classes) to the actual types that should be used
    // where they are required.
    $aliases = [
        ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage::class =>
          ASVoting\Repo\ProposedMotionStorage\FakeProposedMotionStorage::class,

        \ASVoting\Repo\VotingMotionStorage\VotingMotionStorage::class =>
        \ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage::class,
    ];

    // Delegate the creation of types to callables.
    $delegates = [
        \Pdo::class => 'createPDO',
        \Doctrine\ORM\EntityManager::class => 'createDoctrineEntityManager',

        ASVoting\Repo\ProposedMotionStorage\FakeProposedMotionStorage::class => 'createFakeProposedMotionStorage',
    ];

    // Define some params that can be injected purely by name.
    $params = [];

    $prepares = [
    ];

    $defines = [];

    foreach ($testDoubles as $className => $implementation) {
        if (is_object($implementation) == true) {
            if ($className === get_class($implementation)) {
                $shares[$className] = $implementation;
            }
            else {
                $aliases[$className] = get_class($implementation);
                $shares[get_class($implementation)] = $implementation;
            }
        }
        else {
            $aliases[$className] = $implementation;
        }
    }


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
//}
