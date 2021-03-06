<?php

declare(strict_types = 1);

use Danack\Console\Application;

// Holds functions that convert exceptions into command
// line output for use in the command line tools.

function cliHandleInjectionException(Application $console, \Auryn\InjectionException $ie)
{
    fwrite(STDERR, "time: " . date(\ASVoting\App::DATE_TIME_FORMAT) . " ");
    fwrite(STDERR, getTextForException($ie) . "\n");
    fwrite(STDERR, "Dependency chain:\n");
    fwrite(STDERR, implode("\n  ", $ie->getDependencyChain()));
    fwrite(STDERR, "\n");

    exit(-1);
}

function cliHandleGenericException(Application $console, \Exception $e)
{
    fwrite(STDERR, "time: " . date(\ASVoting\App::DATE_TIME_FORMAT) . "\n");
    fwrite(STDERR, getTextForException($e) . "\n");
    exit(-1);
}
