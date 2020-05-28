<?php

declare(strict_types = 1);

namespace ASVoting\ApiController;

use ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage;
use ASVoting\Repo\VotingMotionStorage\VotingMotionStorage;
use SlimAuryn\Response\JsonResponse;

class Motions
{
    public function getProposedMotions(ProposedMotionStorage $proposedMotionRepo)
    {
        $motions = $proposedMotionRepo->getProposedMotions();
        [$error, $data] = convertToValue($motions);
        if ($error !== null) {
            // TODO - do something appropriate here.
        }

        return new JsonResponse($data);
    }

    public function getMotionsBeingVotedOn(
        VotingMotionStorage $votingMotionStorage
    ) {
        $votingMotions = $votingMotionStorage->getVotingMotions();
        [$error, $data] = convertToValue($votingMotions);
        if ($error !== null) {
            // TODO - do something appropriate here.
        }


        return new JsonResponse($data);
    }
}
