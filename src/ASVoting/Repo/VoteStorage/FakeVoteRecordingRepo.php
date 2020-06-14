<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VoteStorage;

use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;
use ASVoting\Model\VotingMotionWithQuestions;
use ASVoting\Model\VoteRecorded;
use ASVoting\Model\VotingQuestionWithChoices;

class FakeVoteRecordingRepo implements VoteRecordingRepo
{

    /** @var VotingQuestionWithChoices[]  */
    private array $votingQuestions;

    /**
     * @var VoteRecorded[]
     */
    private array $votesRecorded;

    /**
     * @param VotingQuestionWithChoices[] $votingQuestions
     * @param VoteRecorded[] $votesRecorded
     */
    public function __construct(array $votingQuestions, array $votesRecorded)
    {
        foreach ($votingQuestions as $votingQuestion) {
            if (!$votingQuestion instanceof VotingQuestionWithChoices) {
                throw new \Exception("entries in $votingQuestions must be instance of VoteRecorded");
            }
        }

        foreach ($votesRecorded as $voteRecorded) {
            if (!$voteRecorded instanceof VoteRecorded) {
                throw new \Exception("entries in $votesRecorded must be instance of VoteRecorded");
            }
        }

        $this->votingQuestions = $votingQuestions;
        $this->votesRecorded = $votesRecorded;
    }

    /**
     * @return VoteRecorded[]
     */
    public function getVotesRecorded()
    {
        return $this->votesRecorded;
    }


    public function recordVote(VoteToRecord $voteToRecord): VoteRecorded
    {
        $voteRecorded = convertVoteToRecordToVoteRecorded($voteToRecord);
        $this->votesRecorded[] = $voteRecorded;

        return $voteRecorded;
    }

    private function getQuestionForVoteToDelete(
        VoteToDelete $voteToDelete
    ): VotingQuestionWithChoices {
        foreach ($this->votingQuestions as $votingQuestion) {
            if ($voteToDelete->getQuestionId() === $votingQuestion->getId()) {
                return $votingQuestion;
            }
        }

        throw new \Exception("Unknown question.");
    }


    public function deleteVote(VoteToDelete $voteToDelete)
    {
        $newVotesRecorded = [];
        $voteToDeleteFound = false;

        $question = $this->getQuestionForVoteToDelete($voteToDelete);

        foreach ($this->votesRecorded as $voteRecorded) {
            if ($voteToDelete->getQuestionId() == $question->getId()) {
                if ($voteToDelete->getUserId() == $voteRecorded->getUserId()) {
                    $voteToDeleteFound = true;
                    continue;
                }
            }
            $newVotesRecorded[] = $voteRecorded;
        }

        if ($voteToDeleteFound === false) {
            throw new \Exception("voteToDelete not found in votesRecorded");
        }

        $this->votesRecorded = $newVotesRecorded;
    }
}
