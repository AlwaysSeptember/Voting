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
use ParamsTest\Integration\ReviewScore;

/**
 * A question that is being asked in a proposed motion.
 *
 * Each question will have a list of choices for the answer.
 *
 */
class ProposedQuestion implements InputParameterList
{
    use ToArray;

    private string $text;
    private string $voting_system;

    /**
     * @var ProposedChoice[]
     */
    private array $choices;

    const VOTING_SYSTEM_FIRST_POST = 'first_past_post';

    const VOTING_SYSTEM_STV = 'single_transferable_vote';

    /**
     *
     * @param string $text
     * @param string $voting_system
     * @param int $motion_id
     * @param ProposedChoice[] $choices
     */
    public function __construct(
        string $text,
        string $voting_system,
        $choices
    ) {
        $this->text = $text;
        $this->voting_system = $voting_system;
        $this->choices = $choices;
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
