<?php

declare(strict_types = 1);

namespace ASVoting\Model;

class VotingMotionWithQuestionsClosed extends VotingMotionWithQuestions
{
    public function getState(): string
    {
        return self::STATE_CLOSED;
    }
}
