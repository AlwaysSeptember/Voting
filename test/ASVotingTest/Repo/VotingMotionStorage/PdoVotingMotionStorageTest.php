<?php

declare(strict_types = 1);

namespace ASVotingTest\Repo\VotingMotionStorage;

use ASVoting\Model\VotingMotionWithQuestions;
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

        $proposedMotion = fakeProposedMotion(__METHOD__);
        $votingMotion = $pdoVotingMotionStorage->openVotingMotion($proposedMotion);
        $this->assertInstanceOf(VotingMotionWithQuestions::class, $votingMotion);


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
     * @param VotingMotionWithQuestions[] $votingMotions
     * @param VotingMotionWithQuestions $particularVotingMotion
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
