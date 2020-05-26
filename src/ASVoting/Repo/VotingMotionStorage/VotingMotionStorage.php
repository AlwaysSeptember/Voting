<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\VotingMotion;
use ASVoting\Model\ProposedMotion;

/**
 * Local storage for motions being voted on .
 *
 * This is the authoratative storage for motions that
 * are being voted on
 *
 */
interface VotingMotionStorage
{
    /**
     * @return VotingMotion[]
     */
    public function getVotingMotion();

    public function proposedMotionAlreadyVoting(
        string $externalSource,
        ProposedMotion $proposedMotion
    ): bool;

    /**
     * Creates a VotingMotion from an ProposedMotion.
     *
     * Throws an exception if the ProposedMotion is a duplicate.
     *
     * @param string $externalSource
     * @param ProposedMotion $proposedMotion
     *
     * // TODO - this is not good. External source needs to be pat of ProposedMotion
     */
    public function createVotingMotion(
        string $externalSource,
        ProposedMotion $proposedMotion
    ): VotingMotion;
}
