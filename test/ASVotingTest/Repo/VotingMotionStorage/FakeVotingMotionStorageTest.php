<?php

declare(strict_types = 1);

namespace ASVotingTest\Repo\VotingMotionStorage;

use ASVoting\Model\VotingMotionWithQuestions;
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

        $proposedMotion = fakeProposedMotion(__CLASS__ . '::' . '__METHOD__',);
        $votingMotion = $fakeVotingMotionStorage->openVotingMotion($proposedMotion);
        $this->assertInstanceOf(VotingMotionWithQuestions::class, $votingMotion);
    }


    /**
     * @covers \ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage
     */
    public function testProposedMotionAlreadyVoting()
    {
        $fakeVotingMotionStorage = new FakeVotingMotionStorage([]);

        $proposedMotion = fakeProposedMotion(__CLASS__ . '::' . '__METHOD__',);

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
