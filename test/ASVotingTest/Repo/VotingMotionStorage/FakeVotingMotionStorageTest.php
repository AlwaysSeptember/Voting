<?php

declare(strict_types = 1);

namespace ASVotingTest\Repo\VotingMotionStorage;

use ASVoting\Model\VotingMotion;
use ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage;
use ASVotingTest\BaseTestCase;

/**
 * @coversNothing
 */
class FakeVotingMotionStorageTest extends BaseTestCase
{
    /**
     * @covers \ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage
     */
    public function testBasic()
    {
        $fakeVotingMotionStorage = new FakeVotingMotionStorage([]);
        $this->assertEmpty($fakeVotingMotionStorage->getVotingMotions());

        $proposedMotion = fakeProposedMotion();
        $votingMotion = $fakeVotingMotionStorage->createVotingMotion(
            'wat',
            $proposedMotion
        );
        $this->assertInstanceOf(VotingMotion::class, $votingMotion);
    }


    /**
     * @covers \ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage
     * @group broken
     */
    public function testProposedMotionAlreadyVoting()
    {
        $fakeVotingMotionStorage = new FakeVotingMotionStorage([]);

        $proposedMotion = fakeProposedMotion();

        $alreadyVoting = $fakeVotingMotionStorage->proposedMotionAlreadyVoting(
            'john',
            $proposedMotion
        );
        $this->assertFalse($alreadyVoting);

        $fakeVotingMotionStorage->createVotingMotion(
            'wat',
            $proposedMotion
        );
        $alreadyVoting = $fakeVotingMotionStorage->proposedMotionAlreadyVoting(
            'john',
            $proposedMotion
        );
        $this->assertTrue($alreadyVoting);
    }
}
