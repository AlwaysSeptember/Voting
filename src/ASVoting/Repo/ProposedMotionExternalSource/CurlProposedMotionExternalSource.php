<?php

declare(strict_types = 1);

namespace ASVoting\Repo\ProposedMotionExternalSource;

use ASVoting\Repo\ProposedMotionExternalSource\ProposedMotionExternalSource;
use ASVoting\Model\ProposedMotion;
use ASVoting\Model\Choice;
use ASVoting\Model\Question;

function convertDataToMotion($data)
{
    $questions = [];
    foreach($data['questions'] as $question) {
        $choices = [];

        foreach($question['choices'] as $choice) {
            $choices[] = new Choice($choice['text']);
        }

        $questions[] = new Question($question['text'], $question['voting_system'], $choices);
    }

    $proposedMotion = new ProposedMotion(
         $data['type'],
         $data['name'],
         \DateTimeImmutable::createFromFormat( \DateTime::RFC3339, '2020-07-02T12:00:00Z'),
         \DateTimeImmutable::createFromFormat( \DateTime::RFC3339, '2020-07-02T12:00:00Z'),
         $questions
    );

    return $proposedMotion;
}

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