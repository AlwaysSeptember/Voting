<?php

declare(strict_types = 1);

namespace ASVoting\Repo\VotingMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Model\VotingMotion;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use ASVoting\Exception\DuplicateEntryException;

// I dislike how this prevents me from storing the data naturally
//
//class DoctrineVotingMotionStorage implements VotingMotionStorage
//{
//    /** @var EntityManager */
//    private $em;
//
//    public function __construct(EntityManager $em)
//    {
//        $this->em = $em;
//    }
//
//    public function getVotingMotions()
//    {
//        throw new \Exception("getVotingMotion not implemented yet.");
//    }
//
//    public function proposedMotionAlreadyVoting(
//        string $externalSource,
//        ProposedMotion $proposedMotion
//    ): bool {
//        throw new \Exception("proposedMotionAlreadyVoting not implemented yet.");
//    }
//
//    public function createVotingMotion(
//        string $externalSource,
//        ProposedMotion $proposedMotion
//    ): VotingMotion {
//
//        $votingMotion = createVotingMotionFromProposedMotion($proposedMotion);
//
//        try {
//            $this->em->persist($votingMotion);
//            $this->em->flush();
//        }
//        catch (UniqueConstraintViolationException $e) {
//            throw new DuplicateEntryException(
//                "Username already used",
//                $e->getCode(),
//                $e
//            );
//        }
//
//        return $votingMotion;
//    }
//}
