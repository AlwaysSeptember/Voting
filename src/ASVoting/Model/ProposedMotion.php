<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;

class ProposedMotion
{
    use ToArray;

    private string $type;

    private string $name;

    private \DateTimeImmutable $start_datetime;

    private \DateTimeImmutable $close_datetime;

    /**
     * @var Question[]
     */
    private array $questions;

    /**
     *
     * @param string $type
     * @param string $name
     * @param \DateTimeImmutable $start_datetime
     * @param \DateTimeImmutable $close_datetime
     * @param Question[] $questions
     */
    public function __construct(
        string $type,
        string $name,
        \DateTimeImmutable $start_datetime,
        \DateTimeImmutable $close_datetime,
        $questions
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->start_datetime = $start_datetime;
        $this->close_datetime = $close_datetime;
        $this->questions = $questions;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDatetime(): \DateTimeImmutable
    {
        return $this->start_datetime;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCloseDatetime(): \DateTimeImmutable
    {
        return $this->close_datetime;
    }

    /**
     * @return Question[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
}
