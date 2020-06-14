<?php

declare(strict_types = 1);

namespace ASVotingTest\Model;

use ASVotingTest\BaseTestCase;
use ASVoting\Model\VotingMotionWithQuestionsOpen;

/**
 * @coversNothing
 */
class VotingMotionTest extends BaseTestCase
{
    /**
     * @covers \ASVoting\Model\ProposedMotion
     */
    public function testConversionWorks()
    {
        // create and encode
        $votingMotions = fakeVotingMotions(__METHOD__ );
        [$errors, $arrayOfData] = convertToValue($votingMotions);
        $json = json_encode_safe($arrayOfData);

        // Decode and recreate
        $decodedData = json_decode_safe($json);

        foreach ($decodedData as $datum) {
            $proposedMotion = VotingMotionWithQuestionsOpen::createFromArray($datum);
            $this->assertInstanceOf(VotingMotionWithQuestionsOpen::class, $proposedMotion);
        }
    }
}
