<?php

declare(strict_types = 1);

namespace ASVoting\CliController;

use ASVoting\Processor\ProcessorCloseVotingMotion;
use function LoopingExec\continuallyExecuteCallable;

class ProcessCloseVotingMotion
{
    private ProcessorCloseVotingMotion $processorCloseVotingMotion;

    /**
     * @param ProcessorCloseVotingMotion $processorCloseVotingMotion
     */
    public function __construct(ProcessorCloseVotingMotion $processorCloseVotingMotion)
    {
        $this->processorCloseVotingMotion = $processorCloseVotingMotion;
    }

    public function run()
    {
        $callable = function () {
            echo "ProcessCloseVotingMotion \n";
            $this->processorCloseVotingMotion->run();
            sleep(1);
        };

        continuallyExecuteCallable(
            $callable,
            $maxRunTime = 600,
            $millisecondsBetweenRuns = 10 * 1000
        );
    }
}
