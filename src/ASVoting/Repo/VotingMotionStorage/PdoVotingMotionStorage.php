<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotionWithQuestionsOpen;
use ASVoting\Model\VotingMotionWithQuestionsClosed;
use ASVoting\PdoSimple;

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

    public function getClosedVotingMotions()
    {
        [$query, $tableColumns] = getSQLInfoForVotingMotions();
        $query = sprintf($query, implode(", ", $tableColumns));
        try {
            $rows = $this->pdo->fetchAll(
                $query,
                [
                    ':voting_motion_state' => VotingMotionWithQuestionsOpen::STATE_CLOSED
                ]
            );
        }
        catch (\Throwable $t) {
            echo $t->getMessage();
        }

        $votingMotionsById = extractVotingMotionsData($rows);

        $votingMotions = [];
        foreach ($votingMotionsById as $id => $data) {
            $votingMotions[] = VotingMotionWithQuestionsClosed::createFromArray($data);
        }

        return $votingMotions;
    }


    public function getOpenVotingMotions()
    {
        [$query, $tableColumns] = getSQLInfoForVotingMotions();
        $query = sprintf($query, implode(", ", $tableColumns));

        $rows = $this->pdo->fetchAll(
            $query,
            [
                ':voting_motion_state' => VotingMotionWithQuestionsOpen::STATE_OPEN
            ]
        );

        $votingMotionsById = extractVotingMotionsData($rows);

        $votingMotions = [];
        foreach ($votingMotionsById as $id => $data) {
            $votingMotions[] = VotingMotionWithQuestionsOpen::createFromArray($data);
        }

        return $votingMotions;
    }

    public function isProposedMotionAlreadyOpened(ProposedMotion $proposedMotion): bool
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


    public function openVotingMotion(ProposedMotion $proposedMotion): VotingMotionWithQuestionsOpen
    {
        $votingMotion = createVotingMotionFromProposedMotion($proposedMotion);

        try {
            $this->pdo->beginTransaction();

            $data = [
                'id' => $votingMotion->getId(),
                'type' => $votingMotion->getType(),
                'name' => $votingMotion->getName(),
                'state' => $votingMotion->getState(),
                'proposed_motion_source' => $votingMotion->getProposedMotionSource(),
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


    public function closeVotingMotion(VotingMotionWithQuestionsOpen $votingMotionOpen): VotingMotionWithQuestionsClosed
    {
        $sql = <<< SQL
update voting_motion
set state = :closed_state
where id = :voting_motion_id
SQL;

        $params = [
            ':closed_state' => \ASVoting\Model\VotingMotionWithQuestions::STATE_CLOSED,
            ':voting_motion_id' => $votingMotionOpen->getId()
        ];

        $this->pdo->execute($sql, $params);

        $rawData = $votingMotionOpen->toArray();
        $closedVotingMotion = VotingMotionWithQuestionsClosed::createFromArray($rawData);

        return $closedVotingMotion;
    }
}
