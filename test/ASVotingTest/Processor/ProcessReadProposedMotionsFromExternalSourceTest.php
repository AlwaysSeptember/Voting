<?php

declare(strict_types = 1);

namespace ASVotingTest\Processor;

use ASVoting\Processor\ProcessReadProposedMotionsFromExternalSource;
use ASVoting\Repo\ProposedMotionStorage\FakeProposedMotionStorage;
use ASVoting\Repo\ProposedMotionExternalSource\EmptyProposedMotionExternalSource;
use ASVoting\Repo\ProposedMotionExternalSource\FakeProposedMotionExternalSource;
use ASVotingTest\BaseTestCase;

/**
 * @coversNothing
 */
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
        $this->assertEmpty($proposedMotionStorage->getProposedMotions());
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
