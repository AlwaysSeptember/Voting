<?php

declare(strict_types = 1);

namespace ASVoting\CliController;

use ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource;
use ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage;
use ASVoting\Processor\ProcessReadProposedMotionsFromExternalSource;

use function LoopingExec\continuallyExecuteCallable;

class ProcessWatchForProposedMotions
{
    private ProcessReadProposedMotionsFromExternalSource $watchForProposedMotions;

    /**
     *
     * @param ProcessReadProposedMotionsFromExternalSource $watchForProposedMotions
     */
    public function __construct(ProcessReadProposedMotionsFromExternalSource $watchForProposedMotions)
    {
        $this->watchForProposedMotions = $watchForProposedMotions;
    }

    public function run()
    {
        $callable = function () {
            echo "ProcessWatchForProposedMotions \n";
            $this->watchForProposedMotions->run();
            sleep(1);
        };

        continuallyExecuteCallable(
            $callable,
            $maxRunTime = 600,
            $millisecondsBetweenRuns = 10 * 1000
        );
    }
}
