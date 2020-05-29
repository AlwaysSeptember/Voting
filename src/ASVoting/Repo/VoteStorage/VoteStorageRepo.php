<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VoteStorage;

use ASVoting\Model\VotingMotion;
use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;

interface VoteStorageRepo
{
    /**
     * @param VotingMotion $votingMotion
     * @return \ASVoting\Model\VoteRecorded[]
     */
    public function getVotesForMotion(VotingMotion $votingMotion);

    public function recordVote(VoteToRecord $vote);

    public function deleteVote(VoteToDelete $vote);
}
