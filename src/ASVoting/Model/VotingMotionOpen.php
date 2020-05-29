<?php

declare(strict_types = 1);

namespace ASVoting\Model;

class VotingMotionOpen extends VotingMotion
{
    public function getState(): string
    {
        return self::STATE_OPEN;
    }
}
