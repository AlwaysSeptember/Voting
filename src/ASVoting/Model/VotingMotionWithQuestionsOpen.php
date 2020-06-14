<?php

declare(strict_types = 1);

namespace ASVoting\Model;

class VotingMotionWithQuestionsOpen extends VotingMotionWithQuestions
{
    public function getState(): string
    {
        return self::STATE_OPEN;
    }
}
