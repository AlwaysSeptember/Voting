<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;

class Choice
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
}
