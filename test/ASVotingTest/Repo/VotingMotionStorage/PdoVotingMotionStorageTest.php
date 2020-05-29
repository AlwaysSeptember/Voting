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
     */
    public function testBasic()
    {
        $pdoVotingMotionStorage = $this->injector->make(PdoVotingMotionStorage::class);

        $proposedMotion = fakeProposedMotion();
        $votingMotion = $pdoVotingMotionStorage->openVotingMotion($proposedMotion);
        $this->assertInstanceOf(VotingMotion::class, $votingMotion);

        // TODO - read voting motion back from DB.
    }

    /**
     * @covers \ASVoting\Repo\VotingMotionStorage\PdoVotingMotionStorage
     * @group wip
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

    public function testClosing()
    {
        $pdoVotingMotionStorage = $this->injector->make(PdoVotingMotionStorage::class);

        $proposedMotion = fakeProposedMotion(
            null,
            null,
            'does testClosing motions work?'
        );
        $votingMotion = $pdoVotingMotionStorage->openVotingMotion($proposedMotion);

        $openList = $pdoVotingMotionStorage->getOpenVotingMotions();
        $this->assertListContains($openList, $votingMotion);

        $closeVotingMotion = $pdoVotingMotionStorage->closeVotingMotion($votingMotion);
        $closedList = $pdoVotingMotionStorage->getClosedVotingMotions();
        $this->assertListContains($closedList, $closeVotingMotion);
    }
}
