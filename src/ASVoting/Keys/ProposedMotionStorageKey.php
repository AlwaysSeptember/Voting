<?php

declare(strict_types = 1);

namespace ASVoting\Keys;

class ProposedMotionStorageKey
{
    /**
     * @param string $externalSource
     * @return string
     */
    public static function getAbsoluteKeyName(string $externalSource) : string
    {
        return __CLASS__ . ':' . hash("sha256", $externalSource);
    }
}
