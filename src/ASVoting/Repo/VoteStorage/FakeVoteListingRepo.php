<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VoteStorage;

use ASVoting\Model\VotingMotionWithQuestions;
use ASVoting\Model\VotingMotionWithQuestionsWithVotes;
use ASVoting\Exception\DataIntegrityException;

class FakeVoteListingRepo implements VoteListingRepo
{
    /** @var VotingMotionWithQuestionsWithVotes[] */
    private $votingQuestionWithVotesList;

    /**
     *
     * @param VotingMotionWithQuestionsWithVotes[] $votingMotionWithVotesList
     */
    public function __construct(array $votingMotionWithVotesList)
    {
        $this->votingQuestionWithVotesList = $votingMotionWithVotesList;
    }

    public function getMotionWithVotes(VotingMotionWithQuestions $votingMotion): VotingMotionWithQuestionsWithVotes
    {
        foreach ($this->votingQuestionWithVotesList as $votingQuestionWithVotes) {
            if ($votingQuestionWithVotes->getId() === $votingMotion->getId()) {
                return $votingQuestionWithVotes;
            }
        }

        throw new DataIntegrityException("Voting Motion not found.");
    }
}
