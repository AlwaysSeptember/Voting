<?php

declare(strict_types = 1);

namespace ASVoting\Model;

class VotingMotionWithQuestionsCancelled extends VotingMotionWithQuestions
{
    public function getState(): string
    {
        return self::STATE_CANCELLED;
    }
}
