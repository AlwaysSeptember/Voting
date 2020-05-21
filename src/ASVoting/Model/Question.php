<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;

class Question
{
    use ToArray;

    private string $text;
    private string $voting_system;

    const VOTING_SYSTEM_FIRST_POST = 'first_past_post';
    const VOTING_SYSTEM_STV = 'single_transferable_vote';

    /**
     * @var Choice[]
     */
    private array $choices;

    /**
     *
     * @param string $text
     * @param string $voting_system
     * @param int $motion_id
     * @param Choice[] $choices
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
     * @return Choice[]
     */
    public function getChoices(): array
    {
        return $this->choices;
    }
}
