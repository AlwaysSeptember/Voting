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
        $this->assertEmpty($fakeVotingMotionStorage->getOpenVotingMotions());

        $proposedMotion = fakeProposedMotion();
        $votingMotion = $fakeVotingMotionStorage->openVotingMotion($proposedMotion);
        $this->assertInstanceOf(VotingMotion::class, $votingMotion);




    }


    /**
     * @covers \ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage
     */
    public function testProposedMotionAlreadyVoting()
    {
        $fakeVotingMotionStorage = new FakeVotingMotionStorage([]);

        $proposedMotion = fakeProposedMotion();

        $alreadyVoting = $fakeVotingMotionStorage->isProposedMotionAlreadyOpened(
            $proposedMotion
        );
        $this->assertFalse($alreadyVoting);

        $fakeVotingMotionStorage->openVotingMotion($proposedMotion);
        $alreadyVoting = $fakeVotingMotionStorage->isProposedMotionAlreadyOpened(
            $proposedMotion
        );
        $this->assertTrue($alreadyVoting);
    }
}
