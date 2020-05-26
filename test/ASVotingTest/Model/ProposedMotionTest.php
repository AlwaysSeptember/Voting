<?php

declare(strict_types = 1);

namespace ASVotingTest\Model;

use ASVotingTest\BaseTestCase;
use ASVoting\Model\ProposedMotion;


/**
 * @coversNothing
 */
class ProposedMotionTest extends BaseTestCase
{
    /**
     * @covers \ASVoting\Model\ProposedMotion
     * @group wip
     */
    public function testConversionWorks()
    {
        // create and encode
        $proposedMotions = fakeProposedMotions();
        [$errors, $arrayOfData] = convertToValue($proposedMotions);
        $json = json_encode_safe($arrayOfData);

        // Decode and recreate
        $decodedData = json_decode_safe($json);

        foreach ($decodedData as $datum) {
            $proposedMotion = ProposedMotion::createFromArray($datum);
            $this->assertInstanceOf(ProposedMotion::class, $proposedMotion);
        }
    }
}
