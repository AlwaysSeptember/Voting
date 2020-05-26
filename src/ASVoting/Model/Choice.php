<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;
use Params\ExtractRule\GetString;
use Params\InputParameter;
use Params\InputParameterList;
use Params\ProcessRule\MinLength;
use Params\ProcessRule\MaxLength;

class Choice implements InputParameterList
{
    use ToArray;

    private string $text;

    /**
     *
     * @param string $text
     * @param int $question_id
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
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
                new MinLength(1),
                new MaxLength(2048)
            )
        ];
    }
}
