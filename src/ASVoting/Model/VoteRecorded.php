<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;
use Params\ExtractRule\GetString;
use Params\InputParameter;
use Params\InputParameterList;
use Params\ProcessRule\MaxLength;
use Params\ProcessRule\MinLength;
use Params\Create\CreateFromArray;
use Params\Create\CreateFromJson;
use Params\Create\CreateFromVarMap;

/**
 * This is a vote that has been recorded and stored in the DB
 */
class VoteRecorded implements InputParameterList
{
    use ToArray;

    use CreateFromArray;
    use CreateFromJson;
    use CreateFromVarMap;

    private string $id;

    private string $user_id;

    private string $question_id;

    // This will need to become a list of preferred choices.
    private string $choice_id;

    /**
     *
     * @param string $user_id
     * @param string $question_id
     * @param string $choice
     */
    public function __construct(
        string $id,
        string $user_id,
        string $question_id,
        string $choice_id
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->question_id = $question_id;
        $this->choice_id = $choice_id;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getQuestionId(): string
    {
        return $this->question_id;
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
    public function getChoiceId(): string
    {
        return $this->choice_id;
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
                'user_id',
                new GetString(),
                new MinLength(4),
                new MaxLength(2048)
            ),
            new InputParameter(
                'question_id',
                new GetString(),
                new MinLength(4),
                new MaxLength(256)
            ),
            new InputParameter(
                'choice_id',
                new GetString(),
                new MinLength(4),
                new MaxLength(256)
            ),
        ];
    }
}
