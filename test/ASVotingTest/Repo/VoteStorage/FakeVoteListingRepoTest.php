<?php

declare(strict_types = 1);

namespace ASVotingTest\Repo\VoteStorage;

use ASVoting\Model\VoteToDelete;
use ASVoting\Model\VoteToRecord;
use ASVoting\Model\VotingQuestionWithChoices;
use ASVoting\Repo\VoteStorage\FakeVoteRecordingRepo;
use ASVotingTest\BaseTestCase;
use ASVoting\Repo\VoteStorage\FakeVoteListingRepo;
use ASVoting\Exception\DataIntegrityException;
use ASVoting\Model\VotingMotionWithQuestionsWithVotes;

class FakeVoteListingRepoTest extends BaseTestCase
{
    // This test is more to make sure the creating fake data works.
    public function testBasic()
    {
        $votingMotionKnown = fakeOpenVotingMotion(__METHOD__);

        $votingMotionWithVotes = fakeVotesForVotingMotion($votingMotionKnown);
        $fakeVoteListingRepo = new FakeVoteListingRepo([$votingMotionWithVotes]);

        $retrievedMotionWithVotes = $fakeVoteListingRepo->getMotionWithVotes($votingMotionKnown);
        $this->assertInstanceOf(
            VotingMotionWithQuestionsWithVotes::class,
            $retrievedMotionWithVotes
        );

        $votingMotionUnknown = fakeOpenVotingMotion(__METHOD__);
        $this->expectException(DataIntegrityException::class);
        $fakeVoteListingRepo->getMotionWithVotes($votingMotionUnknown);
    }


    /**
     * @covers \ASVoting\Repo\VoteStorage\FakeVoteRecordingRepo
     */
    public function testBasicRecordingAndDeleting()
    {
        $votingMotion = fakeOpenVotingMotion(__METHOD__);

        [$question, $voteToRecord] = fakeVoteToRecordFromVotingMotion($votingMotion);

        /** @var  $question VotingQuestionWithChoices */

        /** @var  $voteToRecord VoteToRecord */

        $votingStorageRepo = new FakeVoteRecordingRepo([$question], []);
        $votesRecorded = $votingStorageRepo->getVotesRecorded();
        $this->assertEmpty($votesRecorded);

        $recordedVote = $votingStorageRepo->recordVote($voteToRecord);
        $votesRecorded = $votingStorageRepo->getVotesRecorded();
        $this->assertCount(1, $votesRecorded);

        $voteToDelete = new VoteToDelete(
            $recordedVote->getUserId(),
            $question->getId()
        );

        $votingStorageRepo->deleteVote($voteToDelete);

        $votesRecorded = $votingStorageRepo->getVotesRecorded();
        $this->assertCount(0, $votesRecorded);
    }
}
