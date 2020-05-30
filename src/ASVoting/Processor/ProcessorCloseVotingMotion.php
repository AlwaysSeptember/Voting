<?php

declare(strict_types = 1);

namespace ASVoting\Processor;

use ASVoting\Repo\VotingMotionStorage\VotingMotionStorage;

class ProcessorCloseVotingMotion
{
    private VotingMotionStorage $votingMotionStorage;

    /**
     * @param VotingMotionStorage $votingMotionStorage
     */
    public function __construct(VotingMotionStorage $votingMotionStorage)
    {
        $this->votingMotionStorage = $votingMotionStorage;
    }

    public function run()
    {
        $openedVotingMotions = $this->votingMotionStorage->getOpenVotingMotions();

        foreach ($openedVotingMotions as $openedVotingMotion) {
            if (shouldOpenVotingMotionBeClosed($openedVotingMotion) !== true) {
                continue;
            }

            $openedVotingMotion = $this->votingMotionStorage->closeVotingMotion(
                $openedVotingMotion
            );

            // TODO - log event $openedVotingMotion closed.
        }
    }
}
