<?php

declare(strict_types = 1);

use ASVoting\Model\ProposedChoice;
use ASVoting\Model\ProposedMotion;
use ASVoting\Model\ProposedQuestion;
use ASVoting\Model\VoteRecorded;
use ASVoting\Model\VoteToRecord;
use ASVoting\Model\VotingChoice;
use ASVoting\Model\VotingMotionOpen;
use ASVoting\Model\VotingMotion;
use ASVoting\Model\VotingQuestion;
use Ramsey\Uuid\Uuid;

function fakeProposedMotion(
    string $motionName,
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

    if ($startTime === null && $endTime === null) {
        $startTime = createTimeInPast(65);
        $endTime = createTimeInFuture(65);
    }
    else if ($endTime === null) {
        // Start time is not null
        $endTime = createTimeAfterTime(65, $startTime);
    }
    else if ($startTime === null) {
        // End time is not null
        $startTime = createTimeBeforeTime(65, $endTime);
    }

    // This should be unique to avoid duplicate exceptions in the DB.
    $source = sprintf(
        'https://github.com/AlwaysSeptember/test/blob/master/voting/food_question_%s.json',
        random_int(100000000, 900000000)
    );

    return new ProposedMotion(
        "personal_opinion",
        $motionName,
        $source,
        $startTime,
        $endTime,
        $questions
    );
}

/**
 * @return ProposedMotion[]
 */
function fakeProposedMotions(string $name)
{
    $proposedMotions = [];

    $proposedMotions[] = fakeProposedMotion($name);

    return $proposedMotions;
}
function fakeOpenVotingQuestion()
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

    return new VotingQuestion(
        Uuid::uuid4()->toString(),
        "What ice cream is best?",
        ProposedQuestion::VOTING_SYSTEM_FIRST_POST,
        $choices
    );
}

function fakeOpenVotingMotion(
    string $motionName,
    \DateTimeInterface $startTime = null,
    \DateTimeInterface $endTime = null
) {
    $questions[] = fakeOpenVotingQuestion();

    if ($startTime === null) {
        $startTime = createTimeInPast(65);
    }

    if ($endTime === null) {
        $endTime = createTimeInFuture(65);
    }

    // This should be unique to avoid duplicate exceptions in the DB.
    $source = sprintf(
        'https://github.com/AlwaysSeptember/test/blob/master/voting/food_question_%s.json',
        random_int(100000000, 900000000)
    );

    return new VotingMotionOpen(
        Uuid::uuid4()->toString(),
        "personal_opinion",
        $motionName,
        $source,
        $startTime,
        $endTime,
        $questions
    );
}

/**
 * @return VotingMotionOpen[]
 */
function fakeVotingMotions(string $name)
{
    $votingMotions = [];

    $votingMotions[] = fakeOpenVotingMotion($name);

    return $votingMotions;
}

function fakeVoteRecordedFromVotingMotion(VotingMotion $votingMotion)
{
    $firstQuestion = $votingMotion->getQuestions()[0];
    $firstChoice = $firstQuestion->getChoices()[0];

    $data = [
        'user_id' => '12345',
        'question_id' => $firstQuestion->getId(),
        'choice_id' => $firstChoice->getId()
    ];

    return VoteRecorded::createFromArray($data);
}




function fakeVoteToRecordFromVotingMotion(VotingMotion $votingMotion)
{
    $firstQuestion = $votingMotion->getQuestions()[0];
    $firstChoice = $firstQuestion->getChoices()[0];

    $data = [
        'user_id' => '12345',
        'question_id' => $firstQuestion->getId(),
        'choice' => $firstChoice->getText()
    ];

    return VoteToRecord::createFromArray($data);
}


function createTimeAfterTime(int $minutes, DateTimeInterface $dateTime): DateTimeImmutable
{
    $timeOffset = new DateInterval('PT' . $minutes . 'M');
    return $dateTime->add($timeOffset);
}

function createTimeBeforeTime(int $minutes, DateTimeInterface $dateTime): DateTimeImmutable
{
    $timeOffset = new DateInterval('PT' . $minutes . 'M');
    return $dateTime->sub($timeOffset);
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
