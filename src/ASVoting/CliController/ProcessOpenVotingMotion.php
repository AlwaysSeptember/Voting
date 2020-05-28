<?php

declare(strict_types = 1);

namespace ASVoting\CliController;

use ASVoting\Processor\ProcessProposedMotionsNeedOpening;

use function LoopingExec\continuallyExecuteCallable;

class ProcessOpenVotingMotion
{
    private ProcessProposedMotionsNeedOpening $processProposedMotionsNeedOpening;

    public function __construct(ProcessProposedMotionsNeedOpening $processProposedMotionsNeedOpening)
    {
        $this->processProposedMotionsNeedOpening = $processProposedMotionsNeedOpening;
    }


    public function run()
    {
        $callable = function () {
            echo "ProcessOpenVotingMotion \n";
            $this->processProposedMotionsNeedOpening->run();
            sleep(1);
        };

        continuallyExecuteCallable(
            $callable,
            $maxRunTime = 600,
            $millisecondsBetweenRuns = 10 * 1000
        );
    }
}
