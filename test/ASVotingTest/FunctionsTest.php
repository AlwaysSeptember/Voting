<?php

declare(strict_types=1);

namespace ASVotingTest;

use ASVoting\Model\ProposedMotion;
use Params\Create\CreateFromArray;

/**
 * @coversNothing
 */
class FunctionsTest extends BaseTestCase
{
    /**
     * @covers \ASVoting\Model\ProposedMotion
     */
    public function testMotionCreationIntrinsic()
    {
        // https://github.com/AlwaysSeptember/test/tree/master/voting
        // https://github.com/AlwaysSeptember/test/tree/master/voting/food_question.json
        $json = file_get_contents(__DIR__ . '/../data/food_question.json');
        $data = json_decode_safe($json);
        $data['source'] = 'https://github.com/AlwaysSeptember/test/tree/master/voting/food_question.json';

        $motion = ProposedMotion::createFromArray($data);
        $this->assertInstanceOf(ProposedMotion::class, $motion);
    }
}
