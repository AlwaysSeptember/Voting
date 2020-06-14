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
 * This is the data for an API call that should be passed when a user is
 * voting on a question.
 *
 */
class VoteToRecord implements InputParameterList
{
    use ToArray;

    use CreateFromArray;

    use CreateFromJson;

    use CreateFromVarMap;

    // This is going to require auth token
    private string $user_id;

    // This will need to become a list of preferred choices.
    private string $choice_id;

    /**
     * @param string $user_id
     * @param string $choice
     */
    public function __construct(
        string $user_id,
        string $choice_id
    ) {
        $this->user_id = $user_id;
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
                'user_id',
                new GetString(),
                new MinLength(4),
                new MaxLength(2048)
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
