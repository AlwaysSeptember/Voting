<?php

declare(strict_types = 1);

namespace ASVotingTest\Repo\VotingMotionStorage;

use ASVoting\Model\VotingMotion;
use ASVoting\Repo\VotingMotionStorage\PdoVotingMotionStorage;
use ASVotingTest\BaseTestCase;

/**
 * @coversNothing
 * @group db

 */
class PdoVotingMotionStorageTest extends BaseTestCase
{
    /**
     * @covers \ASVoting\Repo\VotingMotionStorage\PdoVotingMotionStorage
     * @group wip
     */
    public function testBasic()
    {
        $pdoVotingMotionStorage = $this->injector->make(PdoVotingMotionStorage::class);

        $proposedMotion = fakeProposedMotion(__METHOD__);
        $votingMotion = $pdoVotingMotionStorage->openVotingMotion($proposedMotion);
        $this->assertInstanceOf(VotingMotion::class, $votingMotion);


        $this->assertSame(
            $proposedMotion->getSource(),
            $votingMotion->getProposedMotionSource()
        );


        $this->assertWithinOneSecord(
            $proposedMotion->getCloseDatetime(),
            $votingMotion->getCloseDatetime()
        );
        $this->assertWithinOneSecord(
            $proposedMotion->getStartDatetime(),
            $votingMotion->getStartDatetime()
        );

    }

    /**
     * @covers \ASVoting\Repo\VotingMotionStorage\PdoVotingMotionStorage
     */
    public function testBasicReadingWriting()
    {
        $pdoVotingMotionStorage = $this->injector->make(PdoVotingMotionStorage::class);
        $pdoVotingMotionStorage->getOpenVotingMotions();
    }

    /**
     * @param VotingMotion[] $votingMotions
     * @param VotingMotion $particularVotingMotion
     */
    private function assertListContains(array $votingMotions, $particularVotingMotion)
    {
        foreach ($votingMotions as $votingMotion) {
            if ($particularVotingMotion->getId() === $votingMotion->getId()) {
                return;
            }
        }

        $this->fail("list did not contain expected voting motion");
    }

    /**
     * @group wip
     */
    public function testClosing()
    {
        $pdoVotingMotionStorage = $this->injector->make(PdoVotingMotionStorage::class);

        $proposedMotion = fakeProposedMotion(
            __METHOD__,
            null,
            null,
        );
        $votingMotion = $pdoVotingMotionStorage->openVotingMotion($proposedMotion);

        $openList = $pdoVotingMotionStorage->getOpenVotingMotions();
        $this->assertListContains($openList, $votingMotion);

        $closeVotingMotion = $pdoVotingMotionStorage->closeVotingMotion($votingMotion);
        $closedList = $pdoVotingMotionStorage->getClosedVotingMotions();
        $this->assertListContains($closedList, $closeVotingMotion);
    }
}
