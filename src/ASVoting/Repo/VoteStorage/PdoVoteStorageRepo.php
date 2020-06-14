<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VoteStorage;

use ASVoting\Model\VoteRecorded;
use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;
use ASVoting\Model\VotingMotionWithQuestions;
use ASVoting\Model\VotingMotionWithQuestionsClosed;
use ASVoting\Model\VotingMotionWithQuestionsOpen;
use ASVoting\Model\VotingMotionWithQuestionsWithVotes;
use ASVoting\Model\VotingQuestionWithVotes;
use ASVoting\Repo\VoteStorage\VoteListingRepo;
use ASVoting\PdoSimple;
use ASVoting\Model\VotingQuestionWithChoices;
use ASVoting\Model\VotingQuestion;
use ASVoting\Exception\MotionNotAvailableForVoting;
use Ramsey\Uuid\Uuid;
use ASVoting\Model\VotingMotionOpen;
use ASVoting\Exception\QuestionUnknownException;

class PdoVoteStorageRepo implements VoteListingRepo, VoteRecordingRepo
{
    private PdoSimple $pdoSimple;

    /**
     *
     * @param PdoSimple $pdoSimple
     */
    public function __construct(PdoSimple $pdoSimple)
    {
        $this->pdoSimple = $pdoSimple;
    }


    public function getMotionWithVotes(VotingMotionWithQuestions $votingMotion): VotingMotionWithQuestionsWithVotes
    {
        throw new \Exception("getMotionWithVotes not implemented yet.");
    }

    public function getQuestionWithVotes(string $questionId): VotingQuestionWithVotes
    {
        $query = getSQLInfoForVotingQuestion();

        $rows = $this->pdoSimple->fetchAll(
            $query,
            [':voting_question_id' => $questionId]
        );

        $votingQuestionListData = extractVotingQuestionListData($rows);

        if (array_key_exists($questionId, $votingQuestionListData) === false) {
            throw new QuestionUnknownException();
        }

        $votingQuestionData = $votingQuestionListData[$questionId];

        $queryForVoteRecords = <<< SQL
select 
  vote_record.id,
  vote_record.user_id,
  vote_record.choice_id
from
  vote_record
left join
  voting_choice
on 
  voting_choice.id = vote_record.choice_id
where
  voting_choice.question_id = :voting_question_id
SQL;

        $voteRecordsData = $this->pdoSimple->fetchAll(
            $queryForVoteRecords,
            [':voting_question_id' => $questionId]
        );

        $data = [
            'voting_question' => $votingQuestionData,
            'votes' => $voteRecordsData
        ];

        return VotingQuestionWithVotes::createFromArray($data);
    }

//    public function getQuestionFromDBByChoiceId(string $choiceId)
//    {
//        $query = <<< SQL
//select
//  voting_question.id,
//  voting_question.text,
//  voting_question.question_id
//from
//  voting_choice
//inner join
//  voting_question
//on
//  voting_choice.question_id = voting_question.id
//where
//  voting_choice.id = :voting_choice_id
//limit 1
//SQL;
//
//        $data = $this->pdoSimple->fetchOneAsDataOrNull(
//            $query,
//            [':voting_choice_id' => $choiceId]
//        );
//
//        if ($data === null) {
//            throw new \Exception("Question not found for choiceId $choiceId");
//        }
//
//        return VotingQuestion::createFromArray($data);
//    }


    public function getMotionFromDBByChoiceId(string $choiceId): ?VotingMotionOpen
    {
        $query = <<< SQL
select
  voting_motion.id,
  voting_motion.type,
  voting_motion.name,
  voting_motion.state,
  voting_motion.proposed_motion_source,
  voting_motion.start_datetime,
  voting_motion.close_datetime
from
  voting_choice
inner join
  voting_question
on
  voting_choice.question_id = voting_question.id
inner join
  voting_motion
on
  voting_question.motion_id = voting_motion.id
where 
  voting_choice.id = :voting_choice_id
limit 1
SQL;

        $data = $this->pdoSimple->fetchOneAsDataOrNull(
            $query,
            [':voting_choice_id' => $choiceId]
        );

        if ($data === null) {
            return null;
        }

        return VotingMotionOpen::createFromArray($data);
    }


    /**
     * @param VoteToRecord $voteToRecord
     * @return VoteRecorded
     * @throws \Params\Exception\ValidationException
     */
    public function recordVote(VoteToRecord $voteToRecord): VoteRecorded
    {
        $motionFromDB = $this->getMotionFromDBByChoiceId($voteToRecord->getChoiceId());
        if ($motionFromDB === null) {
            $message = sprintf(
                "Motion for choice %s is not available for voting.",
                $voteToRecord->getChoiceId()
            );

            throw new MotionNotAvailableForVoting(
                $message
            );
        }

        // TODO - this needs to be double checked.
//        if ($motionFromDB->isOpenForVoting() !== true) {
//            throw new \Exception("Motion is not open for voting");
//        }

        $data = [
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $voteToRecord->getUserId(),
            'choice_id' =>  $voteToRecord->getChoiceId()
        ];

        $voteRecorded = VoteRecorded::createFromArray($data);

        $this->pdoSimple->insertSimple('vote_record', $voteRecorded->toArray());

        return $voteRecorded;
    }

    /**
     * @param VoteToDelete $voteToDelete
     * @return bool If the vote was deleted correctly.
     * @throws \Exception
     */
    public function deleteVote(VoteToDelete $voteToDelete): bool
    {
        $query = <<< SQL
delete
  vote_record
from 
  vote_record
left join 
  voting_choice
on
  voting_choice.id = vote_record.choice_id

WHERE
  vote_record.user_id = :user_id and
  voting_choice.question_id = :question_id
SQL;

        $data = [
            'user_id' => $voteToDelete->getUserId(),
            'question_id' => $voteToDelete->getQuestionId()
        ];

        $numberRowsDeleted  = $this->pdoSimple->execute($query, $data);
        if ($numberRowsDeleted !== 1) {
            return false;
        }
        return true;
    }
}
