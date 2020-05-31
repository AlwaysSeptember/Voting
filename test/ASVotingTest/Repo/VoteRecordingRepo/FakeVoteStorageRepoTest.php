<?php

declare(strict_types = 1);

namespace ASVotingTest\Repo\VoteRecordingRepo;

use ASVotingTest\BaseTestCase;
use ASVoting\Repo\VoteRecordingRepo\FakeVoteRecordingRepo;
use ASVoting\Model\VoteRecorded;

/**
 * @coversNothing
 */
class FakeVoteStorageRepoTest extends BaseTestCase
{

    /**
     * @covers \ASVoting\Repo\VoteRecordingRepo\FakeVoteRecordingRepo
     */
    public function testBasic()
    {
        $votingMotion = fakeOpenVotingMotion(__METHOD__ );

        $voteToRecord = fakeVoteToRecordFromVotingMotion($votingMotion);

        $votingStorageRepo = new FakeVoteRecordingRepo([], []);
        $votesRecorded = $votingStorageRepo->getVotesRecorded();
        $this->assertEmpty($votesRecorded);

        $recordedVote = $votingStorageRepo->recordVote($voteToRecord);
        $votesRecorded = $votingStorageRepo->getVotesRecorded();
        $this->assertCount(1, $votesRecorded);
    }
}
