<?php

declare(strict_types = 1);

namespace ASVoting\Processor;

use ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource;
use ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage;

class ProcessReadProposedMotionsFromExternalSource
{
    private ProposedMotionExternalSource $proposedMotionExternalSource;
    private ProposedMotionStorage $proposedMotionStorage;

    public function __construct(
        ProposedMotionExternalSource $proposedMotionExternalSource,
        ProposedMotionStorage $proposedMotionStorage
    ) {
        $this->proposedMotionExternalSource = $proposedMotionExternalSource;
        $this->proposedMotionStorage = $proposedMotionStorage;
    }

    public function run()
    {
        $externalSource = "https://api.github.com/repos/alwaysseptember/voting/contents/test/data";
        $proposedMotions = $this->proposedMotionExternalSource->getProposedMotionsFromExternalSource($externalSource);

        $this->proposedMotionStorage->storeProposedMotions(
            $externalSource,
            $proposedMotions
        );
    }
}
