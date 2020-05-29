<?php

declare(strict_types = 1);

namespace ASVotingTest\Processor;

use ASVoting\Processor\ProcessCloseVotingMotion;
use ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage;
use ASVotingTest\BaseTestCase;

/**
 * @coversNothing
 */
class ProcessCloseVotingMotionTest extends BaseTestCase
{
    public function testWorksWithNoData()
    {
        // Setup storage in correct state
        $votingMotionStorage = new FakeVotingMotionStorage([]);

        // create the object we're going to test
        $process = new ProcessCloseVotingMotion(
            $votingMotionStorage
        );

        // run the code under test
        $process->run();

        // Assert the results
        // Check to see if any voting motions were read from storage.
        $motions = $votingMotionStorage->getOpenVotingMotions();
        $this->assertEmpty($motions);

    }

    public function testWorksWithOneVotingMotionButDoesNotNeedClosed()
    {
        $timeInFuture = createTimeInFuture(5);
        $votingMotion = fakeOpenVotingMotion($timeInFuture);

        $votingMotionStorage = new FakeVotingMotionStorage($votingMotion);

        $process = new ProcessCloseVotingMotion(
            $votingMotionStorage
        );

        $process->run();

        $openVotingMotions = $votingMotionStorage->getOpenVotingMotions();
        $this->assertCount(1, $openVotingMotions);
        $closedVotingMotions = $votingMotionStorage->getClosedVotingMotions();
        $this->assertCount(0, $closedVotingMotions);
    }

    public function testWithOpenVotingMotionThatNeedsClosed()
    {
        $timeToCloseVote = createTimeInPast(180);
        $timeToOpenVote = createTimeInPast(250);
        $votingMotion = fakeOpenVotingMotion($timeToOpenVote, $timeToCloseVote);

        $votingMotionStorage = new FakeVotingMotionStorage($votingMotion);

        $process = new ProcessCloseVotingMotion(
            $votingMotionStorage
        );

        $process->run();

        $votingMotions = $votingMotionStorage->getOpenVotingMotions();
        $this->assertCount(0, $votingMotions);
    }
}