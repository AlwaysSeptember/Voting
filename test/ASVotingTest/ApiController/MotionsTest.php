<?php

declare(strict_types=1);

namespace ASVotingTest\ApiController;

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

        // TIFF - create the appropriate VotingMotionStorage with zero entries.
        // \ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage

        $result = $controller->getMotionsBeingVotedOn();

        // TIFF - assert some things.
    }


    /**
     * @covers \ASVoting\ApiController\Motions::getMotionsBeingVotedOn
     */
    public function test_getMotionsBeingVotedOnNotEmpty()
    {
        $controller = new Motions();
        // TIFF - create the appropriate VotingMotionStorage with one or more entries.
        // \ASVoting\Repo\VotingMotionStorage\FakeVotingMotionStorage
        $result = $controller->getMotionsBeingVotedOn();

        // TIFF - assert some things.
    }

}
