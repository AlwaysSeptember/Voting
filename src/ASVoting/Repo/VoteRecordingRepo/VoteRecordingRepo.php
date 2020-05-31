<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VoteRecordingRepo;

use ASVoting\Model\VotingMotion;
use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;
use ASVoting\Model\VoteRecorded;

/**
 * This records votes for questions.
 */
interface VoteRecordingRepo
{
    public function recordVote(VoteToRecord $vote): VoteRecorded;

    public function deleteVote(VoteToDelete $vote);
}
