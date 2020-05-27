<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionExternalSource;

use ASVoting\Model\ProposedMotion;

/**
 *
 * This is a service that reads proposed motions from where
 * they have been created on external sources.
 *
 * The external sources will be things like github repos, other
 * people's servers, twitter bots...
 */
interface ProposedMotionExternalSource
{
    /**
     * @return ProposedMotion[]
     */
    public function getProposedMotionsFromExternalSource(string $source);
}
