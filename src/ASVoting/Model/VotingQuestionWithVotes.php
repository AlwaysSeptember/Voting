<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;
use Params\Create\CreateFromArray;
use Params\ExtractRule\GetArrayOfType;
use Params\ExtractRule\GetString;
use Params\ExtractRule\GetType;
use Params\InputParameter;
use Params\InputParameterList;
use Params\ProcessRule\MaxLength;
use Params\ProcessRule\MinLength;
use Params\ProcessRule\ValidDatetime;
use ASVoting\Model\ProposedQuestion;
use ASVoting\Model\VoteRecorded;

/**
 *
 * @TODO - figure out what how this class will be access and improve the
 * functions to make access easier.
 *
 */
class VotingQuestionWithVotes implements InputParameterList
{
    use CreateFromArray;

    private VotingQuestionWithChoices $votingQuestion;

    /**
     * @var \ASVoting\Model\VoteRecorded[]
     */
    private array $votesRecorded;

    /**
     *
     * @param VotingQuestionWithChoices $votingQuestion
     * @param VoteRecorded[] $votesRecorded
     */
    public function __construct(
        VotingQuestionWithChoices $votingQuestion,
        $votesRecorded
    ) {
        $this->votingQuestion = $votingQuestion;
        $this->votesRecorded = $votesRecorded;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->votingQuestion->getId();
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->votingQuestion->getText();
    }

    /**
     * @return string
     */
    public function getVotingSystem(): string
    {
        return $this->votingQuestion->getVotingSystem();
    }

    /**
     * @return VotingChoice[]
     */
    public function getChoices(): array
    {
        return $this->votingQuestion->getChoices();
    }

    public function getVotesRecorded(): array
    {
        return $this->votesRecorded;
    }

    /**
     * @return \Params\InputParameter[]
     */
    public static function getInputParameterList(): array
    {
        return [
            new InputParameter(
                'voting_question',
                GetType::fromClass(VotingQuestionWithChoices::class)
            ),
            new InputParameter(
                'votes',
                new GetArrayOfType(VoteRecorded::class)
            )
        ];
    }
}
