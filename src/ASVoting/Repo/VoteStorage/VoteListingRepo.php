<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VoteStorage;

use ASVoting\Model\VotingMotionWithQuestions;
use ASVoting\Model\VotingMotionWithQuestionsWithVotes;

/**
 * Retrieve VotingMotion with votes information
 */
interface VoteListingRepo
{
    public function getMotionWithVotes(VotingMotionWithQuestions $votingMotion): VotingMotionWithQuestionsWithVotes;
}
