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

/**
 * This is the data that is passed when someone is deleting their vote
 * from a question.
 */
class VoteToDelete implements InputParameterList
{
    use ToArray;

    use CreateFromArray;
    use CreateFromJson;
    use CreateFromVarMap;

    private string $user_id;

    private string $question_id;

    /**
     *
     * @param string $user_id
     * @param string $question_id
     */
    public function __construct(
        string $user_id,
        string $question_id
    ) {
        $this->user_id = $user_id;
        $this->question_id = $question_id;
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
                'question_id',
                new GetString(),
                new MinLength(4),
                new MaxLength(256)
            )
        ];
    }
}
