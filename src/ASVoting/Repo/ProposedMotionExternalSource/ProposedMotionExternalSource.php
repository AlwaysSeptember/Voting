<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionExternalSource;

use ASVoting\Model\ProposedMotion;

interface ProposedMotionExternalSource
{
    /**
     * @return ProposedMotion[]
     */
    public function getProposedMotionsFromExternalSource(string $source);
}