<?php

declare(strict_types = 1);

namespace ASVotingTest\Repo\VoteStorage;

use ASVoting\Model\VoteRecorded;
use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;
use ASVoting\Model\VotingQuestionWithChoices;
use ASVoting\Repo\VoteStorage\PdoVoteStorageRepo;
use ASVoting\Repo\VotingMotionStorage\PdoVotingMotionStorage;
use ASVotingTest\BaseTestCase;
use ASVoting\Exception\MotionNotAvailableForVoting;
use ASVoting\Exception\QuestionUnknownException;

/**
 * @group db
 */
class PdoVoteStorageRepoTest extends BaseTestCase
{
    public function testThrowsOnUnknownQuestion()
    {
        $pdoVoteStorageRepo = $this->injector->make(PdoVoteStorageRepo::class);
        $this->expectException(MotionNotAvailableForVoting::class);
        $id = bin2hex(random_bytes(32));

        $this->expectException(QuestionUnknownException::class);
        $pdoVoteStorageRepo->getQuestionWithVotes(
            $id
        );
    }


    public function testBasic()
    {
        $pdoVoteStorage = $this->injector->make(PdoVoteStorageRepo::class);

        // Setup a fake voting motion
        $pdoVotingMotionStorage = $this->injector->make(PdoVotingMotionStorage::class);
        $proposedMotion = fakeProposedMotion(
            __METHOD__,
            null,
            null,
        );

        // make the motion exist in the DB.
        $votingMotion = $pdoVotingMotionStorage->openVotingMotion($proposedMotion);
        $votingQuestion = $votingMotion->getQuestions()[0];

        // Get the votes for the first question.
        $questionWithVotes = $pdoVoteStorage->getQuestionWithVotes(
            $votingQuestion->getId()
        );
        $this->assertEmpty($questionWithVotes->getVotesRecorded());

        $user_id = '12345';

        // Record a vote
        $firstChoice = $questionWithVotes->getChoices()[0];
        $voteToRecord = new VoteToRecord(
            $user_id,
            $firstChoice->getId()
        );
        $pdoVoteStorage->recordVote($voteToRecord);

        // Check that the vote exists.
        $questionWithVotes = $pdoVoteStorage->getQuestionWithVotes(
            $votingQuestion->getId()
        );
        $this->assertCount(1, $questionWithVotes->getVotesRecorded());

        // Delete the vote
        $voteToDelete = new VoteToDelete(
            $user_id,
            $votingQuestion->getId()
        );
        $pdoVoteStorage->deleteVote($voteToDelete);

        // Check that the votes for the question are empty.
        $questionWithVotes = $pdoVoteStorage->getQuestionWithVotes(
            $votingQuestion->getId()
        );
        $this->assertEmpty($questionWithVotes->getVotesRecorded());
    }

    public function testDeletingVoteThrowsOnUnknownUserQuestionCombo()
    {
        $this->markTestSkipped();
    }

}
