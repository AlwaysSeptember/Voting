<?php

declare(strict_types=1);

namespace ASVotingTest\ApiController;

use Params\DataLocator\DataStorage;
use Params\DataLocator\InputStorageAye;
use ASVoting\Model\ProposedMotion;
use ASVoting\ApiController\Motions;
use ASVotingTest\BaseTestCase;
use SlimAuryn\Response\JsonResponse;


/**
 * @coversNothing
 */
class MotionsTest extends BaseTestCase
{
    /**
     * @covers \ASVoting\ApiController\Motions::getProposedMotions
     * @group wip
     */
    public function testMotionCreationIntrinsic()
    {
        $injector = createInjector($testDoubles = [], $shareDoubles = []);

        $result = $injector->execute([Motions::class, 'getProposedMotions']);


        $this->assertInstanceOf(JsonResponse::class, $result);
        /** @var  $result JsonResponse */
        $data = json_decode_safe($result->getBody());

        $this->assertInternalType('array', $data);
        foreach ($data as $datum) {
            $proposedMotion = ProposedMotion::createFromArray($datum);
            $this->assertInstanceOf(ProposedMotion::class, $proposedMotion);
        }
    }
}
