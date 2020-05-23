<?php

declare(strict_types = 1);

namespace ASVoting\ApiController;

use ASVoting\Repo\ProposedMotionStorage\ProposedMotionStorage;
use SlimAuryn\Response\JsonResponse;

class Motions
{
    public function getProposedMotions(ProposedMotionStorage $proposedMotionRepo)
    {
        $motions = $proposedMotionRepo->getProposedMotions();

        $data = [];
        foreach ($motions as $motion) {
            $data[] = $motion->toArray();
        }

        return new JsonResponse($data);
    }
}
