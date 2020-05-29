<?php

declare(strict_types = 1);

namespace ASVoting\Model;

class VotingMotionClosed extends VotingMotion
{
    public function getState(): string
    {
        return self::STATE_CLOSED;
    }
}
