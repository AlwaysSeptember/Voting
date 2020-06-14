<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\App;
use ASVoting\ToArray;
use Params\ExtractRule\GetArrayOfType;
use Params\ExtractRule\GetString;
use Params\ExtractRule\GetDatetime;
use Params\InputParameter;
use Params\InputParameterList;
use Params\ProcessRule\MaxLength;
use Params\ProcessRule\MinLength;
use Params\ProcessRule\LaterThanParam;
use Params\Create\CreateFromJson;
use Params\Create\CreateFromArray;

/**
 * A vote that is being/has been voted on with the associated votes.
 *
 *
 */
class VotingMotionWithQuestionsWithVotes
{
    use ToArray;

    private VotingMotionWithQuestions $votingMotion;

    /** @var \ASVoting\Model\VotingQuestionWithVotes[] */
    private array $questions;

    /**
     *
     * @param VotingMotionWithQuestions $votingMotion
     * @param array|VotingQuestionWithVotes[] $questions
     */
    public function __construct(
        VotingMotionWithQuestions $votingMotion,
        $questions
    ) {
        $this->votingMotion = $votingMotion;
        $this->questions = $questions;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->votingMotion->getId();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->votingMotion->getType();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->votingMotion->getName();
    }

    /**
     * @return string
     */
    public function getProposedMotionSource(): string
    {
        return $this->votingMotion->getProposedMotionSource();
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartDatetime(): \DateTimeInterface
    {
        return $this->votingMotion->getStartDatetime();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCloseDatetime(): \DateTimeInterface
    {
        return $this->votingMotion->getCloseDatetime();
    }

    /**
     * @return VotingQuestionWithVotes[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
}
