<?php

declare(strict_types = 1);

namespace ASVoting\Processor;

use ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage;
use ASVoting\Repo\VotingMotionStorage\VotingMotionStorage;

class ProcessProposedMotionsNeedOpening
{
    private ProposedMotionStorage $proposedMotionStorage;

    private VotingMotionStorage $votingMotionStorage;

    /**
     *
     * @param ProposedMotionStorage $proposedMotionStorage
     * @param VotingMotionStorage $votingMotionStorage
     */
    public function __construct(
        ProposedMotionStorage $proposedMotionStorage,
        VotingMotionStorage $votingMotionStorage
    ) {
        $this->proposedMotionStorage = $proposedMotionStorage;
        $this->votingMotionStorage = $votingMotionStorage;
    }

    public function run()
    {
        $proposedMotions = $this->proposedMotionStorage->getProposedMotions();

        foreach ($proposedMotions as $proposedMotion) {
            if (proposedMotionShouldBeOpen($proposedMotion) !== true) {
                continue;
            }

            if ($this->votingMotionStorage->isProposedMotionAlreadyOpened($proposedMotion) === true) {
                continue;
            }

            $votingMotion = $this->votingMotionStorage->openVotingMotion(
                $proposedMotion
            );

            // TODO - log event $votingMotion created.
        }
    }
}
