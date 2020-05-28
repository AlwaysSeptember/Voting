<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionExternalSource;

use ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource;
use ASVoting\Model\ProposedMotion;
use ASVoting\Model\ProposedChoice;
use ASVoting\Model\ProposedQuestion;
use Params\Create\CreateOrErrorFromArray;

class CurlProposedMotionExternalSource implements ProposedMotionExternalSource
{
    public function getProposedMotionsFromExternalSource(string $source): array
    {
        $headers = [ 'User-Agent: AlwaysSeptember' ];
        $files = fetchDataWithHeaders($source, $headers);
        $motions = [];
        foreach ($files as $file) {
            $motionData = fetchDataWithHeaders($file['download_url'], $headers);

            // todo = $motionData['source'] = $source;

            [$object, $errors] = ProposedMotion::createOrErrorFromArray($motionData);

            // TODO - log errors $errors
            if ($object === null) {
                continue;
            }

            $motions[] = $object;
        }
        return $motions;
    }
}
