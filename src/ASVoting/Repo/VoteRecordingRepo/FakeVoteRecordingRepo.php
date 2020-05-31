<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VoteRecordingRepo;

use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;
use ASVoting\Model\VotingMotion;
use ASVoting\Model\VoteRecorded;
use ASVoting\Model\VotingQuestion;

class FakeVoteRecordingRepo implements VoteRecordingRepo
{

    private array $votingQuestions;

    /**
     * @var VoteRecorded[]
     */
    private array $votesRecorded;

    /**
     * @param VotingQuestion[] $votingQuestions
     * @param VoteRecorded[] $votesRecorded
     */
    public function __construct(array $votingQuestions, array $votesRecorded)
    {
        foreach ($votingQuestions as $votingQuestion) {
            if (!$votingQuestion instanceof VotingQuestion) {
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

    public function deleteVote(VoteToDelete $voteToDelete)
    {
        $newVotesRecorded = [];
        $voteToDeleteFound = false;

        foreach ($this->votesRecorded as $voteRecorded) {
            if ($voteToDelete->getQuestionId() == $voteRecorded->getQuestionId()) {
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
