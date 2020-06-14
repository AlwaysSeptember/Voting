<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;
use Params\Create\CreateFromArray;
use Params\Create\CreateFromJson;
use Params\ExtractRule\GetArrayOfType;
use Params\ExtractRule\GetString;
use Params\InputParameter;
use Params\InputParameterList;
use Params\ProcessRule\MaxLength;
use Params\ProcessRule\MinLength;
use Params\ProcessRule\ValidDatetime;
use ASVoting\Model\ProposedQuestion;

/**
 * A question for a motion that is currently being/has been voted on.
 *
 */
class VotingQuestion implements InputParameterList
{
    use CreateFromArray;
    use CreateFromJson;

    /**
     * A unique id for this question.
     *
     */
    private string $id;

    use ToArray;

    private string $text;

    private string $voting_system;

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
        string $voting_system
    ) {
        $this->id = $id;
        $this->text = $text;
        $this->voting_system = $voting_system;
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
        ];
    }
}
