<?php

declare(strict_types = 1);

namespace ASVotingTest\Repo\VotingMotionStorage;

use ASVoting\Model\VotingMotionWithQuestions;
use ASVoting\Repo\VotingMotionStorage\DoctrineVotingMotionStorage;
use ASVotingTest\BaseTestCase;

/**
 * @coversNothing
 * @group db
 */
class DoctrineVotingMotionStorageTest extends BaseTestCase
{
    /**
     * @covers \ASVoting\Repo\VotingMotionStorage\DoctrineVotingMotionStorage
     */
    public function testBasic()
    {
//        $doctrineVotingMotionStorage = $this->injector->make(DoctrineVotingMotionStorage::class);
//        $proposedMotion = fakeProposedMotion();
//        $votingMotion = $doctrineVotingMotionStorage->createVotingMotion('john', $proposedMotion);
//        $this->assertInstanceOf(VotingMotion::class, $votingMotion);
//
//        // TODO - read voting motion back from DB.
    }
}