<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionExternalSource;

use ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource;
use ASVoting\Model\ProposedMotion;

class CurlProposedMotionExternalSource implements ProposedMotionExternalSource
{
    public function getProposedMotionsFromExternalSource(string $source)
    {
        $headers = [ 'User-Agent: AlwaysSeptember' ];
        $files = fetchDataWithHeaders($source, $headers);
        foreach($files as $file) {
            $motionJson = fetchDataWithHeaders($file['download_url'], $headers);
        }
        var_dump($motionJson);
        exit(0);
    }
}