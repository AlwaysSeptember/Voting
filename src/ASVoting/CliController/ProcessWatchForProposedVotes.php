<?php

declare(strict_types = 1);

namespace ASVoting\CliController;

use function LoopingExec\continuallyExecuteCallable;

class ProcessWatchForProposedVotes
{

    public function __construct(
//        StripeEventRepo $stripeEventRepo,
//        StripeEventProcessor $stripeEventProcessor
    ) {
//        $this->stripeEventRepo = $stripeEventRepo;
//        $this->stripeEventProcessor = $stripeEventProcessor;
    }

    public function run()
    {
        $callable = function () {
            $this->runInternal();
        };

        continuallyExecuteCallable(
            $callable,
            $maxRunTime = 600,
            $millisecondsBetweenRuns = 10 * 1000
        );
    }

    public function runInternal()
    {
        echo "ProcessWatchForProposedVotes \n";
        sleep(1);
//        $stripeEvent = $this->stripeEventRepo->waitForStripeEvent();
//        if ($stripeEvent === null) {
//            return;
//        }
//
//        $this->stripeEventProcessor->processStripeEvent($stripeEvent);
    }
}
