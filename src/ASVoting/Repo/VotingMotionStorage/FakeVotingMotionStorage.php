<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotionWithQuestionsOpen;
use ASVoting\Model\VotingMotionWithQuestionsClosed;

class FakeVotingMotionStorage implements VotingMotionStorage
{
    /**
     * @var VotingMotionWithQuestionsOpen[]
     */
    private array $openVotingMotions = [];


    /**
     * @var VotingMotionWithQuestionsClosed[]
     */
    private array $closedVotingMotions = [];

    /**
     *
     * @param VotingMotionWithQuestionsOpen[] $votingMotion
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
    public function openVotingMotion(ProposedMotion $proposedMotion): VotingMotionWithQuestionsOpen
    {
        $votingMotion = createVotingMotionFromProposedMotion($proposedMotion);
        $this->openVotingMotions[] = $votingMotion;

        return $votingMotion;
    }

    public function closeVotingMotion(VotingMotionWithQuestionsOpen $votingMotionOpen): VotingMotionWithQuestionsClosed
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
        $closedVotingMotion = VotingMotionWithQuestionsClosed::createFromArray($rawData);
        $this->closedVotingMotions[] = $closedVotingMotion;

        return $closedVotingMotion;
    }
}
