<?php

declare(strict_types = 1);

namespace ASVotingTest\Processor;

use ASVoting\Processor\ProcessReadProposedMotionsFromExternalSource;
use ASVoting\Repo\ProposedMotionStorage\FakeProposedMotionStorage;
use ASVoting\Repo\ProposedMotionExternalSource\EmptyProposedMotionExternalSource;
use ASVoting\Repo\ProposedMotionExternalSource\FakeProposedMotionExternalSource;
use ASVotingTest\BaseTestCase;


class ProcessReadProposedMotionsFromExternalSourceTest extends BaseTestCase
{
    /**
     * @covers \ASVoting\Processor\ProcessReadProposedMotionsFromExternalSource
     */
    public function testWorksWithNoData()
    {
        $proposedMotionStorage = new FakeProposedMotionStorage();
        $emptyProposedMotionExternalSource = new FakeProposedMotionExternalSource([]);

        $process = new ProcessReadProposedMotionsFromExternalSource(
            $emptyProposedMotionExternalSource,
            $proposedMotionStorage
        );

        $process->run();
        // Check to see if any proposed motions were read from the external
        // source, and saved to our local storage.
        $motions = $proposedMotionStorage->getProposedMotions();
        $this->assertEmpty($motions);
    }

    public function testWorksWithOneProposedMotion()
    {
        $proposedMotionStorage = new FakeProposedMotionStorage();
        $proposedMotions = fakeProposedMotions();
        $proposedMotionExternalSource = new FakeProposedMotionExternalSource($proposedMotions);

        $process = new ProcessReadProposedMotionsFromExternalSource(
            $proposedMotionExternalSource,
            $proposedMotionStorage
        );

        $process->run();
        $storedProposedMotions = $proposedMotionStorage->getProposedMotions();

        $this->assertCount(1, $storedProposedMotions);
    }
}
