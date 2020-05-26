<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\ProposedChoice;
use ASVoting\Model\ProposedQuestion;

class FakeProposedMotionStorage implements ProposedMotionStorage
{
    /**
     * @var ProposedMotion[]
     */
    private array $proposedMotions = [];

    /**
     * @param string $externalSource
     * @param ProposedMotion[] $proposedMotions
     */
    public function storeProposedMotions(
        string $externalSource,
        array $proposedMotions
    ): void {

        foreach ($proposedMotions as $proposedMotion) {
            $this->proposedMotions[] = $proposedMotion;
        }
    }

    public function getProposedMotions()
    {
        return $this->proposedMotions;
    }
}
