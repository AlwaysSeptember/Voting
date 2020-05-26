<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotion;

class FakeVotingMotionStorage implements VotingMotionStorage
{
    /**
     * @var VotingMotion[]
     */
    private array $votingMotions = [];

    /**
     *
     * @param VotingMotion[] $votingMotion
     */
    public function __construct(array $votingMotion)
    {
        $this->votingMotions = $votingMotion;
    }

    public function getVotingMotions(): array
    {
        return $this->votingMotions;
    }

    public function proposedMotionAlreadyVoting(
        string $externalSource,
        ProposedMotion $proposedMotion
    ): bool {
        // TODO - this needs fixing to do the right thing.
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

        $this->votingMotions[$externalSource] = $votingMotion;

        return $votingMotion;
    }
}
