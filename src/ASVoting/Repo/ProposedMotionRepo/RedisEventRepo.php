<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionRepo;

use ASVoting\Model\ProposedMotion;
use Redis;

class RedisEventRepo implements ProposedMotionRepo
{
    /** @var Redis */
    private $redis;

    /**
     *
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function getProposedMotions()
    {
        throw new \Exception("getProposedMotions not implemented yet.");
    }
}
