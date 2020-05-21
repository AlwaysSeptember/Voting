<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionRepo;

use ASVoting\Model\ProposedMotion;

interface ProposedMotionRepo
{
    /**
     * @return ProposedMotion[]
     */
    public function getProposedMotions();
}
