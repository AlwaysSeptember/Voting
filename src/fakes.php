<?php

declare(strict_types = 1);

use ASVoting\Model\ProposedChoice;
use ASVoting\Model\ProposedMotion;
use ASVoting\Model\ProposedQuestion;
use ASVoting\Model\VotingChoice;
use ASVoting\Model\VotingMotion;
use ASVoting\Model\VotingQuestion;
use Ramsey\Uuid\Uuid;

function fakeProposedMotion(
    \DateTimeInterface $startTime = null,
    \DateTimeInterface $endTime = null
): ProposedMotion {

    $choices = [];

    $choices[] = new ProposedChoice("Strawberry");
    $choices[] = new ProposedChoice("Chocolate");
    $choices[] = new ProposedChoice("Vanilla");

    $questions[] = new ProposedQuestion(
        "What ice cream is best?",
        ProposedQuestion::VOTING_SYSTEM_FIRST_POST,
        $choices
    );

    if ($startTime === null) {
        $startTime = \DateTimeImmutable::createFromFormat(
            \DateTime::RFC3339,
            '2020-07-02T12:00:00Z'
        );
    }

    if ($endTime === null) {
        $endTime = \DateTimeImmutable::createFromFormat(
            \DateTime::RFC3339,
            '2020-07-07T13:00:00Z'
        );
    }

    return new ProposedMotion(
        "personal_opinion",
        "Question about food",
        'https://github.com/AlwaysSeptember/test/blob/master/voting/food_question.json',
        $startTime,
        $endTime,
        $questions
    );
}

/**
 * @return ProposedMotion[]
 */
function fakeProposedMotions()
{
    $proposedMotions = [];

    $proposedMotions[] = fakeProposedMotion();

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

    // TODO - time will need to be made dynamic instead of hardcoded
    $startTime = \DateTimeImmutable::createFromFormat(
        \DateTime::RFC3339,
        '2020-07-02T12:00:00Z'
    );

    // TODO - time will need to be made dynamic instead of hardcoded
    $endTime = \DateTimeImmutable::createFromFormat(
        \DateTime::RFC3339,
        '2020-07-07T13:00:00Z'
    );

    $votingMotions = [];
    $votingMotions[] = new VotingMotion(
        Uuid::uuid4()->toString(),
        "personal_opinion",
        "Question about food",
        'https://github.com/AlwaysSeptember/test/blob/master/voting/food_question.json',
        $startTime,
        $endTime,
        $questions
    );

    return $votingMotions;
}


function createTimeInFuture(int $minutes): DateTimeImmutable
{
    $now = new DateTimeImmutable();
    $timeOffset = new DateInterval('PT'.$minutes.'M');
    return $now->add($timeOffset);
}

function createTimeInPast(int $minutes): DateTimeImmutable
{
    $now = new DateTimeImmutable();
    $timeOffset = new DateInterval('PT'.$minutes.'M');
    return $now->sub($timeOffset);
}
