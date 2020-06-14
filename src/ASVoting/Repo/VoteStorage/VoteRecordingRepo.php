<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VoteStorage;

use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;
use ASVoting\Model\VoteRecorded;

/**
 * Records votes for questions.
 *
 *
 */
interface VoteRecordingRepo
{
    /**
     * Record a vote for a single question.
     *
     * @param VoteToRecord $vote
     * @return VoteRecorded
     */
    public function recordVote(VoteToRecord $vote): VoteRecorded;

    /**
     * Delete a vote for a single question.
     *
     * @param VoteToDelete $vote
     * @return mixed
     */
    public function deleteVote(VoteToDelete $vote);
}
