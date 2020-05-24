<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\Choice;
use ASVoting\Model\Question;

class FakeProposedMotionStorage implements ProposedMotionStorage
{
    /**
     * @param string $externalSource
     * @param ProposedMotion[] $proposedMotions
     */
    public function storeProposedMotions(
        string $externalSource,
        array $proposedMotions
    ): void {
        echo "You asked me to save " . count($proposedMotions) . " proposed motions.\n";
    }

    public function getProposedMotions()
    {
        $choices = [];

        $choices[] = new Choice("Strawberry");
        $choices[] = new Choice("Chocolate");
        $choices[] = new Choice("Vanilla");

        $questions[] = new Question(
            "What ice cream is best?",
            Question::VOTING_SYSTEM_FIRST_POST,
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
