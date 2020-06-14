<?php

declare(strict_types = 1);

namespace ASVotingTest\Model;

use ASVoting\Model\VoteToRecord;
use ASVotingTest\BaseTestCase;

/**
 * @coversNothing
 * @group wip
 */
class VoteToRecordTest extends BaseTestCase
{
    public function testBasic()
    {
        $votingMotion = fakeOpenVotingMotion(__METHOD__);

        $firstQuestion = $votingMotion->getQuestions()[0];
        $firstChoice = $firstQuestion->getChoices()[0];

        $user_id = '12345';

        $data = [
            'user_id' => $user_id,
//            'question_id' => $firstQuestion->getId(),
            'choice_id' => $firstChoice->getId()
        ];

        $voteToRecord = VoteToRecord::createFromArray($data);

        $this->assertSame($user_id, $voteToRecord->getUserId());
//        $this->assertSame($firstQuestion->getId(), $voteToRecord->getQuestionId());
        $this->assertSame($firstChoice->getId(), $voteToRecord->getChoiceId());
    }
}
