<?php

declare(strict_types = 1);

namespace ASVoting\CliController;

use function LoopingExec\continuallyExecuteCallable;

class ProcessCloseVotingMotion
{
    private ProcessCloseVotingMotion $processCloseVotingMotion;

    /**
     * @param ProcessCloseVotingMotion $processCloseVotingMotion
     */
    public function __construct(ProcessCloseVotingMotion $processCloseVotingMotion)
    {
        $this->processCloseVotingMotion = $processCloseVotingMotion;
    }

    public function run()
    {
        $callable = function () {
            echo "ProcessCloseVotingMotion \n";
            $this->processCloseVotingMotion->run();
            sleep(1);
        };

        continuallyExecuteCallable(
            $callable,
            $maxRunTime = 600,
            $millisecondsBetweenRuns = 10 * 1000
        );
    }
}
