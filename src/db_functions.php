<?php

declare(strict_types = 1);

use ASVoting\Model\VotingQuestionWithChoices;
use ASVoting\Model\VotingQuestionWithVotes;

function extractChoicesForQuestionId($votingQuestionId, $rows)
{
    $choicesById = [];

    foreach ($rows as $row) {
        if ($votingQuestionId !== $row['voting_question_id']) {
            continue;
        }
        $choiceId = $row['voting_choice_id'];

        if (array_key_exists($choiceId, $choicesById) === true) {
            continue;
        }

        $choicesById[$choiceId] = [
            'id' => $row['voting_choice_id'],
            'text' => $row['voting_choice_text'],
        ];
    }

    return $choicesById;
}

function extractQuestionsForMotionId($votingMotionId, $rows)
{
    $questionsById = [];

    foreach ($rows as $row) {
        if ($votingMotionId !== $row['voting_motion_id']) {
            continue;
        }
        $questionId = $row['voting_motion_id'];

        if (array_key_exists($questionId, $questionsById) === true) {
            continue;
        }

        $choices = extractChoicesForQuestionId($questionId, $rows);

        $questionsById[$questionId] = [
            'id' => $row['voting_question_id'],
            'text' => $row['voting_question_text'],
            'voting_system' => $row['voting_question_voting_system'],
            'choices' => $choices
        ];
    }

    return $questionsById;
}

function extractVotingMotionsData(array $rows)
{
    $votingMotionsById = [];

    foreach ($rows as $row) {
        $votingMotionId = $row['voting_motion_id'];
        if (array_key_exists($votingMotionId, $votingMotionsById) === true) {
            continue;
        }

        $questions = extractQuestionsForMotionId($votingMotionId, $rows);

        $votingMotionsById[$votingMotionId] = [
            'id' => $row['voting_motion_id'],
            'type' => $row['voting_motion_type'],
            'name' => $row['voting_motion_name'],
            'proposed_motion_source' => $row['voting_motion_proposed_motion_source'],
            'start_datetime' => $row['voting_motion_start_datetime'],
            'close_datetime' => $row['voting_motion_close_datetime'],
            'questions' => $questions
        ];
    }

    return $votingMotionsById;
}


function extractVoteRecordedArrayDataForQuestionId($votingQuestionId, $rows)
{
    $votesRecordedById = [];

    foreach ($rows as $row) {
        if ($votingQuestionId !== $row['voting_question_id']) {
            continue;
        }

//        $questionId = $row['voting_motion_id'];
//        if (array_key_exists($questionId, $questionsById) === true) {
//            continue;
//        }
//        $choices = extractChoicesForQuestionId($questionId, $rows);

        $votesRecordedById[$votingQuestionId] = [
            'id' => $row['voting_question_id'],
            'user_id' => $row[''],
            'question_id' => $row[''],
            'choice_id' => $row['']
        ];
    }

    return $votesRecordedById;
}


/**
 * @param array $rows
 * @return \ASVoting\Model\VotingQuestionWithChoices
 */
function extractVotingQuestionListData(array $rows): array
{
    $questionsById = [];

    $votingQuestionDataList = [];

    foreach ($rows as $row) {
        $votingQuestionId = $row['voting_question_id'];
        if (array_key_exists($votingQuestionId, $questionsById) === true) {
            continue;
        }

        $choices = extractChoicesForQuestionId($votingQuestionId, $rows);

        $votingQuestionData = [
            'id' => $row['voting_question_id'],
            'text' => $row['voting_question_text'],
            'voting_system' => $row['voting_question_voting_system'],
            'choices' => $choices
        ];

        $votingQuestionDataList[$votingQuestionId] = $votingQuestionData;
    }

    return $votingQuestionDataList;
}


function getSQLInfoForVotingMotions()
{
    $voting_motion_columns = [
        'id',
        'type',
        'name',
        'proposed_motion_source',
        'start_datetime',
        'close_datetime',
    ];

    $voting_question_columns = [
        'id',
        'text',
        'voting_system',
        'motion_id',
    ];

    $voting_choice_columns = [
        'id',
        'text',
        'question_id',
    ];


    $tables = [
        'voting_motion' => $voting_motion_columns,
        'voting_question' => $voting_question_columns,
        'voting_choice' => $voting_choice_columns
    ];

    $tableColumns = [];
    foreach ($tables as $tablename => $columns) {
        foreach ($columns as $column) {
            $tableColumns[] = $tablename . '.' . $column . " as " . $tablename . '_' . $column;
        }
    }


    $query = <<< SQL
select %s
from voting_motion

inner join voting_question
on voting_question.motion_id = voting_motion.id

inner join voting_choice
on voting_choice.question_id = voting_question.id

where voting_motion.state = :voting_motion_state
SQL;

    return [$query, $tableColumns];
}




function getSQLInfoForVotingQuestion()
{
    $voting_question_columns = [
        'id',
        'text',
        'voting_system',
        'motion_id',
    ];

    $voting_choice_columns = [
        'id',
        'text',
        'question_id',
    ];

    $tables = [
        'voting_question' => $voting_question_columns,
        'voting_choice' => $voting_choice_columns
    ];

    $tableColumns = [];
    foreach ($tables as $tablename => $columns) {
        foreach ($columns as $column) {
            $tableColumns[] = $tablename . '.' . $column . " as " . $tablename . '_' . $column;
        }
    }

    $query = <<< SQL
select %s
from voting_question

inner join voting_choice
on voting_choice.question_id = voting_question.id

where voting_question.id = :voting_question_id
SQL;

    $query = sprintf($query, implode("\n, ", $tableColumns));

    return $query;
}


function getSQLInfoForVotingQuestionWithVotes()
{
    $voting_question_columns = [
        'id',
        'text',
        'voting_system',
        'motion_id',
    ];

    $voting_choice_columns = [
        'id',
        'text',
        'question_id',
    ];

    $voting_record_columns = [
        'id',
        'user_id',
        'question_id',
        'choice_id'
    ];

    $tables = [
        'voting_question' => $voting_question_columns,
        'voting_choice' => $voting_choice_columns,
        'vote_record' => $voting_record_columns
    ];

    $tableColumns = [];
    foreach ($tables as $tablename => $columns) {
        foreach ($columns as $column) {
            $tableColumns[] = $tablename . '.' . $column . " as " . $tablename . '_' . $column;
        }
    }

    $query = <<< SQL
select %s
from voting_question

inner join voting_choice
on voting_choice.question_id = voting_question.id

left outer join vote_record
on vote_record.question_id = voting_question.id

where voting_question.id = :voting_question_id
SQL;

    $query = sprintf($query, implode("\n, ", $tableColumns));

    return $query;
}
