<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionStorage;

use ASVoting\Model\ProposedMotion;

/**
 * Local storage for proposed motions.
 *
 * We keep them locally to avoid having to query the external
 * data source continually.
 *
 * But this storage should be considered a cache, not an
 * authoratative data source.
 */
interface ProposedMotionStorage
{
    /**
     * @return ProposedMotion[]
     */
    public function getProposedMotions();

    /**
     * @param string $externalSource
     * @param ProposedMotion[] $proposedMotions
     */
    public function storeProposedMotions(
        string $externalSource,
        array $proposedMotions
    ): void;
}
