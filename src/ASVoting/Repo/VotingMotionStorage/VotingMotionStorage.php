<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotionOpen;
use ASVoting\Model\VotingMotionClosed;

/**
 * Local storage for motions being voted on .
 *
 * This is the authoritative storage for motions that
 * are being voted on
 *
 */
interface VotingMotionStorage
{
    /**
     * @return VotingMotionOpen[]
     */
    public function getOpenVotingMotions();

    /**
     * @return VotingMotionClosed[]
     */
    public function getClosedVotingMotions();


    // TODO - this is probably a bad name. maybe already open?
    public function isProposedMotionAlreadyOpened(ProposedMotion $proposedMotion): bool;

    /**
     * Creates a VotingMotion from an ProposedMotion.
     *
     * Throws an exception if the ProposedMotion is a duplicate.
     *
     * @param ProposedMotion $proposedMotion
     */
    public function openVotingMotion(ProposedMotion $proposedMotion): VotingMotionOpen;

    public function closeVotingMotion(VotingMotionOpen $votingMotionOpen): VotingMotionClosed;
}
