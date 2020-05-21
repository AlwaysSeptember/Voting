<?php

declare(strict_types = 1);

namespace ASVoting\ApiController;

use ASVoting\Repo\ProposedMotionRepo\ProposedMotionRepo;
use SlimAuryn\Response\JsonResponse;

class Motions
{
    public function getProposedMotions(ProposedMotionRepo $proposedMotionRepo)
    {
        $motions = $proposedMotionRepo->getProposedMotions();

        $data = [];
        foreach ($motions as $motion) {
            $data[] = $motion->toArray();
        }

        return new JsonResponse($data);
    }
}
