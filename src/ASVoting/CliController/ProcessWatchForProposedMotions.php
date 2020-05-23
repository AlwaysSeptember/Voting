<?php

declare(strict_types = 1);

namespace ASVoting\CliController;

use ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource;
use ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage;
use function LoopingExec\continuallyExecuteCallable;

class ProcessWatchForProposedMotions
{
    private ProposedMotionExternalSource $proposedMotionExternalSource;
    private ProposedMotionStorage $proposedMotionStorage;

    public function __construct(
        ProposedMotionExternalSource $proposedMotionExternalSource,
        ProposedMotionStorage $proposedMotionStorage
    ) {
        $this->proposedMotionExternalSource = $proposedMotionExternalSource;
        $this->proposedMotionStorage = $proposedMotionStorage;
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
        echo "ProcessWatchForProposedMotions \n";
        $externalSource = "https://github.com/AlwaysSeptember/Voting/tree/master/test/data";

        $proposedMotions = $this->proposedMotionExternalSource->getProposedMotionsFromExternalSource($externalSource);

        $this->proposedMotionStorage->storeProposedMotions(
            $externalSource,
            $proposedMotions
        );
        sleep(1);
    }
}
