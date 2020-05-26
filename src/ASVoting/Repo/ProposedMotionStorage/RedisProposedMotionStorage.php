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
        $externalSource = "https://api.github.com/repos/alwaysseptember/voting/contents/test/data";
        $key = ProposedMotionStorageKey::getAbsoluteKeyName($externalSource);

        $result = $this->redis->get($key);

        if ($result === null) {
            // log no data
            return [];
        }

        if ($result === false) {
            // log no data
            return [];
        }

        $proposedMotionsData = json_decode_safe($result);
        $proposedMotions = [];
        foreach ($proposedMotionsData as $proposedMotionData) {
            $proposedMotions[] = convertDataToMotion($proposedMotionData);
        }

        return $proposedMotions;
    }

    /**
     * @param string $externalSource
     * @param ProposedMotion[] $proposedMotions
     */
    public function storeProposedMotions(
        string $externalSource,
        array $proposedMotions
    ): void {

        $key = ProposedMotionStorageKey::getAbsoluteKeyName($externalSource);
        // Tiff - magic happens in convertToValue
        $dataToStore = convertToValue('john', $proposedMotions);

        $stringToStore = json_encode($dataToStore);

        $this->redis->setex(
            $key,
            24 * 3600, // 1 day,
            $stringToStore
        );
    }
}
