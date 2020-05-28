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
        $votingMotion = $pdoVotingMotionStorage->createVotingMotion('john', $proposedMotion);
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
        $pdoVotingMotionStorage->getVotingMotions();
    }

}
