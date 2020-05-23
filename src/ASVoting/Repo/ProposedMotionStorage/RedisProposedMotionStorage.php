<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionStorage;

use ASVoting\Model\ProposedMotion;
use ASVoting\Keys\ProposedMotionStorageKey;
use Redis;

class RedisProposedMotionStorage implements ProposedMotionStorage
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
        return [];
    }

    /**
     * @param string $externalSource
     * @param ProposedMotion[] $proposedMotions
     */
    public function storeProposedMotions(
        string $externalSource,
        array $proposedMotions
    ): void
    {

        $key = ProposedMotionStorageKey::getAbsoluteKeyName($externalSource);
        // Tiff - magic happens in convertToValue
        $stringToStore = convertToValue('john', $proposedMotions);
        $this->redis->setex(
            $key,
            24 * 3600, // 1 day,
            $stringToStore
        );
    }
}
