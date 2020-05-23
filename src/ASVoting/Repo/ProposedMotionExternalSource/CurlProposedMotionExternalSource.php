<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionExternalSource;

use ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource;
use ASVoting\Model\ProposedMotion;
use ASVoting\Model\Choice;
use ASVoting\Model\Question;

class CurlProposedMotionExternalSource implements ProposedMotionExternalSource
{
    public function getProposedMotionsFromExternalSource(string $source)
    {
        $headers = [ 'User-Agent: AlwaysSeptember' ];
        $files = fetchDataWithHeaders($source, $headers);
        $motions = [];
        foreach($files as $file) {
            $motionData = fetchDataWithHeaders($file['download_url'], $headers);
            $motions[] = convertDataToMotion($motionData);
        }
        return $motions;
    }
}