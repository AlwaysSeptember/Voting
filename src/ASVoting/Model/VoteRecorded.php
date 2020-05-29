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

use Params\Create\CreateFromArray;
use Params\Create\CreateFromJson;
use Params\Create\CreateFromVarMap;

class VoteRecorded implements InputParameterList
{
    use ToArray;

    use CreateFromArray;

    use CreateFromJson;

    use CreateFromVarMap;

    private string $id;

    private string $user_id;

    private string $motion_id;

    private string $question_id;

    // This will need to become a list of preferred choices.
    private string $choice;

    /**
     *
     * @param string $user_id
     * @param string $motion_id
     * @param string $question_id
     * @param string $choice
     */
    public function __construct(
        string $user_id,
        string $motion_id,
        string $question_id,
        string $choice
    ) {
        $this->user_id = $user_id;
        $this->motion_id = $motion_id;
        $this->question_id = $question_id;
        $this->choice = $choice;
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
    public function getMotionId(): string
    {
        return $this->motion_id;
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
    public function getChoice(): string
    {
        return $this->choice;
    }

    /**
     * @return \Params\InputParameter[]
     */
    public static function getInputParameterList(): array
    {

        return [
            new InputParameter(
                'user_id',
                new GetString(),
                new MinLength(4),
                new MaxLength(2048)
            ),
            new InputParameter(
                'motion_id',
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
                'choice',
                new GetString(),
                new MinLength(4),
                new MaxLength(256)
            ),
        ];
    }

}
