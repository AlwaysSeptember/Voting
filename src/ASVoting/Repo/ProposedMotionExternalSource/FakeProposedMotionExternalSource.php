<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionExternalSource;

use ASVoting\Model\ProposedChoice;
use ASVoting\Model\ProposedMotion;
use ASVoting\Model\ProposedQuestion;

class FakeProposedMotionExternalSource implements ProposedMotionExternalSource
{
    /**
     * @var ProposedMotion[]
     */
    private array $proposedMotions;

    /**
     *
     * @param array|ProposedMotion[] $proposedMotions
     */
    public function __construct(array $proposedMotions)
    {
        $this->proposedMotions = $proposedMotions;
    }

    /**
     * @return ProposedMotion[]
     */
    public function getProposedMotionsFromExternalSource(string $source)
    {
        return $this->proposedMotions;
    }
}
