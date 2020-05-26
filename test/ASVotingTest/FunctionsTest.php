<?php

declare(strict_types=1);

namespace ASVotingTest;

use Params\DataLocator\DataStorage;
use Params\DataLocator\InputStorageAye;
use ASVoting\Model\ProposedMotion;

/**
 * @coversNothing
 */
class FunctionsTest extends BaseTestCase
{
    /**
     * @covers ::convertDataToMotion
     */
    public function testMotionCreation()
    {
        $json = file_get_contents(__DIR__ . '/../data/food_question.json');

        $data = json_decode_safe($json);
        $motion = convertDataToMotion($data);

        $this->assertInstanceOf(ProposedMotion::class, $motion);
    }


    /**
     * @covers ::convertDataToMotion
     */
    public function testMotionCreationIntrinsic()
    {
        $json = file_get_contents(__DIR__ . '/../data/food_question.json');

        $motion = ProposedMotion::createFromJson($json);

//        $data = json_decode_safe($json);
//        $motion = convertDataToMotion($data);

        $this->assertInstanceOf(ProposedMotion::class, $motion);
    }

}
