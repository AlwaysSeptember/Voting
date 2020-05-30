<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotionOpen;
use ASVoting\Model\VotingMotionClosed;

class FakeVotingMotionStorage implements VotingMotionStorage
{
    /**
     * @var VotingMotionOpen[]
     */
    private array $openVotingMotions = [];


    /**
     * @var VotingMotionClosed[]
     */
    private array $closedVotingMotions = [];

    /**
     *
     * @param VotingMotionOpen[] $votingMotion
     */
    public function __construct(array $votingMotion)
    {
        $this->openVotingMotions = $votingMotion;
    }

    public function getClosedVotingMotions()
    {
        return $this->closedVotingMotions;
    }

    public function getOpenVotingMotions(): array
    {
        return $this->openVotingMotions;
    }

    public function isProposedMotionAlreadyOpened(ProposedMotion $proposedMotion): bool
    {
        foreach ($this->openVotingMotions as $openVotingMotion) {
            if ($proposedMotion->getSource() === $openVotingMotion->getProposedMotionSource()) {
                return true;
            }
        }

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
    public function openVotingMotion(ProposedMotion $proposedMotion): VotingMotionOpen
    {
        $votingMotion = createVotingMotionFromProposedMotion($proposedMotion);
        $this->openVotingMotions[] = $votingMotion;

        return $votingMotion;
    }

    public function closeVotingMotion(VotingMotionOpen $votingMotionOpen): VotingMotionClosed
    {
        $newOpenVotingMotions = [];

        foreach ($this->openVotingMotions as $openVotingMotion) {
            if ($openVotingMotion->getId() === $votingMotionOpen->getId()) {
                continue;
            }
            $newOpenVotingMotions[] = $openVotingMotion;
        }

        $this->openVotingMotions = $newOpenVotingMotions;

        $rawData = $votingMotionOpen->toArray();
        $closedVotingMotion = VotingMotionClosed::createFromArray($rawData);
        $this->closedVotingMotions[] = $closedVotingMotion;

        return $closedVotingMotion;
    }
}
