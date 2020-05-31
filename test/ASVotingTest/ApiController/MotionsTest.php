<?php

declare(strict_types=1);

namespace ASVotingTest\ApiController;

use ASVoting\Repo\ProposedMotionStorage\FakeProposedMotionStorage;
use ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage;
use ASVoting\Repo\VotingMotionStorage\VotingMotionStorage;
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
     */
    public function test_getProposedMotions()
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

    /**
     * @covers \ASVoting\ApiController\Motions::getMotionsBeingVotedOn
     */
    public function test_getMotionsBeingVotedOnEmpty()
    {
        $controller = new Motions();

        $emptyVotingMotionStorage = new FakeVotingMotionStorage([]);

        $result = $controller->getMotionsBeingVotedOn($emptyVotingMotionStorage);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $data = json_decode_safe($result->getBody());
        $this->assertEmpty($data);
    }


    /**
     * @covers \ASVoting\ApiController\Motions::getMotionsBeingVotedOn
     */
    public function test_getMotionsBeingVotedOnNotEmpty()
    {
        $controller = new Motions();

        $votingMotions = fakeVotingMotions(__METHOD__ );

        $fakeVotingMotionStorage = new FakeVotingMotionStorage($votingMotions);

        $result = $controller->getMotionsBeingVotedOn($fakeVotingMotionStorage);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $data = json_decode_safe($result->getBody());
        $this->assertCount(1, $data);
    }

}
