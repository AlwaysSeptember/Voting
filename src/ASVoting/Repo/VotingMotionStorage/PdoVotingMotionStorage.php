<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotion;

use ASVoting\PdoSimple;

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

    $votingMotions = [];

    foreach ($votingMotionsById as $id => $data) {
        $votingMotions[] = VotingMotion::createFromArray($data);
    }

    return $votingMotions;
}

class PdoVotingMotionStorage implements VotingMotionStorage
{
    private PdoSimple $pdo;

    /**
     *
     * @param PdoSimple $pdo
     */
    public function __construct(PdoSimple $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getVotingMotions()
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

        $voting_choice_columns =[
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
                $tableColumns[] = $tablename . '.' . $column . " as " . $tablename . '_' . $column ;
            }
        }


        $query = <<< SQL
select %s
from voting_motion

inner join voting_question
on voting_question.motion_id = voting_motion.id

inner join voting_choice
on voting_choice.question_id = voting_question.id
SQL;

        $query = sprintf($query, implode(", ", $tableColumns));


        try {
            $rows = $this->pdo->fetchAll(
                $query,
                []
            );
        }
        catch (\Throwable $t) {
            echo $t->getMessage();
        }

        $data = extractVotingMotionsData($rows);
        return $data;
    }

    public function proposedMotionAlreadyVoting(ProposedMotion $proposedMotion): bool
    {
        $query = <<< SQL
select id
from voting_motion
where      
proposed_motion_source = :proposed_motion_source
limit 1
SQL;

        $rowCount = $this->pdo->execute(
            $query,
            [':proposed_motion_source' => $proposedMotion->getSource()]
        );

        if ($rowCount !== 0) {
            return true;
        }

        return false;
    }


    public function createVotingMotion(
        string $externalSource,
        ProposedMotion $proposedMotion
    ): VotingMotion {

        $votingMotion = createVotingMotionFromProposedMotion($proposedMotion);

        try {
            $this->pdo->beginTransaction();

            $data = [
                'id' => $votingMotion->getId(),
                'type' => $votingMotion->getType(),
                'name' => $votingMotion->getName(),
                'proposed_motion_source' => $votingMotion->getProposedMotionsource(),
                'start_datetime' => $votingMotion->getStartDatetime(),
                'close_datetime' => $votingMotion->getCloseDatetime(),
            ];

            $this->pdo->insertSimple('voting_motion', $data);

            foreach ($votingMotion->getQuestions() as $question) {
                $data = [
                    'id' => $question->getId(),
                    'motion_id' => $votingMotion->getId(),
                    'voting_system' => $question->getVotingSystem(),
                    'text' => $question->getText()
                ];

                $this->pdo->insertSimple('voting_question', $data);

                foreach ($question->getChoices() as $choice) {
                    $data = [
                        'id' => $choice->getId(),
                        'question_id' => $question->getId(),
                        'text' => $choice->getText()
                    ];
                    $this->pdo->insertSimple('voting_choice', $data);
                }
            }

            $this->pdo->commit();
        }
        catch (\Throwable $t) {
            $this->pdo->rollBack();
            throw $t;
        }

        return $votingMotion;
    }
}
