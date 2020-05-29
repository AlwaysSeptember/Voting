<?php

declare(strict_types = 1);

namespace ASVoting\Model;

class VotingMotionCancelled extends VotingMotion
{
    public function getState(): string
    {
        return self::STATE_CANCELLED;
    }
}
