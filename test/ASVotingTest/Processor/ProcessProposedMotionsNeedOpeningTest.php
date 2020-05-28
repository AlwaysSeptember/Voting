<?php

declare(strict_types = 1);

namespace ASVotingTest\Processor;

use ASVoting\Model\ProposedMotion;
use ASVoting\Processor\ProcessReadProposedMotionsFromExternalSource;
use ASVoting\Processor\ProcessProposedMotionsNeedOpening;
use ASVoting\Repo\ProposedMotionStorage\FakeProposedMotionStorage;
use ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage;
use ASVotingTest\BaseTestCase;

/**
 * @coversNothing
 */
class ProcessProposedMotionsNeedOpeningTest extends BaseTestCase
{

    /**
     * @covers \ASVoting\Processor\ProcessProposedMotionsNeedOpening
     */
    public function testWorksWithNoData()
    {
        // Setup storage in correct state
        $proposedMotionStorage = new FakeProposedMotionStorage([]);
        $votingMotionStorage = new FakeVotingMotionStorage([]);

        // create the object we're going to test
        $process = new ProcessProposedMotionsNeedOpening(
            $proposedMotionStorage,
            $votingMotionStorage
        );

        // run the code under test
        $process->run();

        // Assert the results
        // Check to see if any proposed motions were read from the external
        // source, and saved to our local storage.
        $motions = $votingMotionStorage->getVotingMotions();
        $this->assertEmpty($motions);
    }

    /**
     * @covers \ASVoting\Processor\ProcessProposedMotionsNeedOpening
     */
    public function testWorksWithOneProposedMotionTooSoonToOpen()
    {
        $startTime = createTimeInFuture(5);
        $proposedMotion = fakeProposedMotion($startTime);

        $proposedMotionStorage = new FakeProposedMotionStorage([$proposedMotion]);

        $votingMotionStorage = new FakeVotingMotionStorage([]);

        // create the object we're going to test
        $process = new ProcessProposedMotionsNeedOpening(
            $proposedMotionStorage,
            $votingMotionStorage
        );

        // run the code under test
        $process->run();

        // Assert the results
        // Check to see if any proposed motions were read from the external
        // source, and saved to our local storage.
        $votingMotions = $votingMotionStorage->getVotingMotions();
        $this->assertCount(0, $votingMotions);
    }

    /**
     * @covers \ASVoting\Processor\ProcessProposedMotionsNeedOpening
     */
    public function testWorksWithOneProposedMotionShouldAlreadyBeClosed()
    {
        $closeTime = createTimeInPast(5);
        $proposedMotion = fakeProposedMotion(null, $closeTime);

        $proposedMotionStorage = new FakeProposedMotionStorage([$proposedMotion]);
        $votingMotionStorage = new FakeVotingMotionStorage([]);

        // create the object we're going to test
        $process = new ProcessProposedMotionsNeedOpening(
            $proposedMotionStorage,
            $votingMotionStorage
        );

        // run the code under test
        $process->run();

        // Assert the results
        // Check to see if any proposed motions were read from the external
        // source, and saved to our local storage.
        $votingMotions = $votingMotionStorage->getVotingMotions();
        $this->assertCount(0, $votingMotions);
    }

    /**
     * @covers \ASVoting\Processor\ProcessProposedMotionsNeedOpening
     */
    public function testWorksWithOneProposedMotionVotingMotionShouldBeOpen()
    {
        $proposedMotion = fakeProposedMotion(
            new \DateTimeImmutable('2020-05-20'),
            new \DateTimeImmutable('2020-07-31')
        );

        $proposedMotionStorage = new FakeProposedMotionStorage([$proposedMotion]);
        $votingMotionStorage = new FakeVotingMotionStorage([]);

        // create the object we're going to test
        $process = new ProcessProposedMotionsNeedOpening(
            $proposedMotionStorage,
            $votingMotionStorage
        );

        // run the code under test
        $process->run();

        // Assert the results
        // Check to see if any proposed motions were read from the external
        // source, and saved to our local storage.
        $votingMotions = $votingMotionStorage->getVotingMotions();
        $this->assertCount(1, $votingMotions);
    }
}
