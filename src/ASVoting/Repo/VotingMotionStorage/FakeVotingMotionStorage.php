<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotion;

class FakeVotingMotionStorage implements VotingMotionStorage
{
    private $votingMotion = [];

    public function getVotingMotion(): array
    {
        return fakeVotingMotions();
    }

    public function proposedMotionAlreadyVoting(
        string $externalSource,
        ProposedMotion $proposedMotion
    ): bool {
        return false;
    }

    /**
     * Creates a VotingMotion from an ProposedMotion.
     *
     * Throws an exception if the ProposedMotion is a duplicate.
     *
     * @param string $externalSource
     * @param ProposedMotion $proposedMotion
     */
    public function createVotingMotion(
        string $externalSource,
        ProposedMotion $proposedMotion
    ): VotingMotion {

        $votingMotion = createVotingMotionFromProposedMotion($proposedMotion);

        $this->votingMotion[$externalSource] = $votingMotion;

        return $votingMotion;
    }
}
