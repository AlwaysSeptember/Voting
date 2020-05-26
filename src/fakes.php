<?php

declare(strict_types = 1);

use ASVoting\Model\ProposedChoice;
use ASVoting\Model\ProposedMotion;
use ASVoting\Model\ProposedQuestion;
use ASVoting\Model\VotingChoice;
use ASVoting\Model\VotingMotion;
use ASVoting\Model\VotingQuestion;
use Ramsey\Uuid\Uuid;

/**
 * @return ProposedMotion[]
 */
function fakeProposedMotions()
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


/**
 * @return VotingMotion[]
 */
function fakeVotingMotions()
{
    $choices = [];

    $choices[] = new VotingChoice(
        Uuid::uuid4()->toString(),
        "Strawberry"
    );
    $choices[] = new VotingChoice(
        Uuid::uuid4()->toString(),
        "Chocolate"
    );
    $choices[] = new VotingChoice(
        Uuid::uuid4()->toString(),
        "Vanilla"
    );

    $questions[] = new VotingQuestion(
        Uuid::uuid4()->toString(),
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

    $votingMotions = [];
    $votingMotions[] = new VotingMotion(
        Uuid::uuid4()->toString(),
        "personal_opinion",
        "Question about food",
        $startTime,
        $endTime,
        $questions
    );

    return $votingMotions;
}
