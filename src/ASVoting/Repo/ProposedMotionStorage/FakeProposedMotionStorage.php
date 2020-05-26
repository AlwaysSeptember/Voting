<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\ProposedChoice;
use ASVoting\Model\ProposedQuestion;

class FakeProposedMotionStorage implements ProposedMotionStorage
{
    private $proposedMotions = [];

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
        $choices = [];

        $choices[] = new ProposedChoice("Strawberry");
        $choices[] = new ProposedChoice("Chocolate");
        $choices[] = new ProposedChoice("Vanilla");

        $questions[] = new ProposedQuestion(
            "What ice cream is best?",
            ProposedQuestion::VOTING_SYSTEM_FIRST_POST,
            $choices
        );

        $startTime = \DateTimeImmutable::createFromFormat(
            \DateTime::RFC3339,
            '2020-07-02T12:00:00Z'
        );

        $endTime = \DateTimeImmutable::createFromFormat(
            \DateTime::RFC3339,
            '2020-07-07T13:00:00Z'
        );

        $proposedMotions = [];
        $proposedMotions[] = new ProposedMotion(
            "personal_opinion",
            "Question about food",
            $startTime,
            $endTime,
            $questions
        );

        return $proposedMotions;
    }
}
