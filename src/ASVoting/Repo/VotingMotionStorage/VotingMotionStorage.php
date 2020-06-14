<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotionWithQuestionsOpen;
use ASVoting\Model\VotingMotionWithQuestionsClosed;

/**
 * Local storage for motions being voted on.
 *
 * This is the authoritative storage for motions that
 * are being voted on
 *
 */
interface VotingMotionStorage
{
    /**
     * Get a complete list of all the motions open for voting.
     *
     *
     * @return VotingMotionWithQuestionsOpen[]
     */
    public function getOpenVotingMotions();

    /**
     * Get a complete list of all the motions no longer open for for voting.
     * This will include both closed and cancelled ones.
     *
     * @return VotingMotionWithQuestionsClosed[]
     */
    public function getClosedVotingMotions();


    /**
     * Check to see if a proposed motion has already been opened.
     *
     * @param ProposedMotion $proposedMotion
     * @return bool
     */
    public function isProposedMotionAlreadyOpened(ProposedMotion $proposedMotion): bool;

    /**
     * Creates a VotingMotion from an ProposedMotion.
     *
     * Throws an exception if the ProposedMotion is a duplicate.
     *
     * @param ProposedMotion $proposedMotion
     */
    public function openVotingMotion(ProposedMotion $proposedMotion): VotingMotionWithQuestionsOpen;


    /**
     * Change a voting motion to be closed, and so no longer able to be voted on.
     * @param VotingMotionWithQuestionsOpen $votingMotionOpen
     * @return VotingMotionWithQuestionsClosed
     */
    public function closeVotingMotion(VotingMotionWithQuestionsOpen $votingMotionOpen): VotingMotionWithQuestionsClosed;
}
