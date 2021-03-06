<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;
use Params\ExtractRule\GetArrayOfType;
use Params\ExtractRule\GetString;
use Params\InputParameter;
use Params\InputParameterList;
use Params\ProcessRule\MaxLength;
use Params\ProcessRule\MinLength;
use Params\ProcessRule\ValidDatetime;
use ASVoting\Model\ProposedQuestion;

/**
 * A question for a motion that is currently being voted on.
 *
 * @Entity @Table(name="voting_question") @HasLifecycleCallbacks
 */
class VotingQuestion implements InputParameterList
{
    /**
     * A unique id for this question.
     * @Id @Column(type="string")
     */
    private string $id;

    use ToArray;

    private string $text;

    private string $voting_system;

    /**
     * @var VotingChoice[]
     *  OneToMany(targetEntity="ASVoting\Model\VotingChoice", mappedBy="")
     */
    private array $choices;

    const VOTING_SYSTEM_FIRST_POST = 'first_past_post';
    const VOTING_SYSTEM_STV = 'single_transferable_vote';

    /**
     * @param string $id
     * @param string $text
     * @param string $voting_system
     * @param int $motion_id
     * @param ProposedChoice[] $choices
     */
    public function __construct(
        string $id,
        string $text,
        string $voting_system,
        $choices
    ) {
        $this->id = $id;
        $this->text = $text;
        $this->voting_system = $voting_system;
        $this->choices = $choices;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getVotingSystem(): string
    {
        return $this->voting_system;
    }

    /**
     * @return ProposedChoice[]
     */
    public function getChoices(): array
    {
        return $this->choices;
    }

    /**
     * @return \Params\InputParameter[]
     */
    public static function getInputParameterList(): array
    {
        return [
            new InputParameter(
                'id',
                new GetString(),
                new MinLength(4),
                new MaxLength(2048)
            ),
            new InputParameter(
                'text',
                new GetString(),
                new MinLength(4),
                new MaxLength(2048)
            ),
            new InputParameter(
                'voting_system',
                new GetString(),
                new MinLength(4),
                new MaxLength(256)
            ),
            new InputParameter(
                'choices',
                new GetArrayOfType(ProposedChoice::class)
            ),
        ];
    }
}
