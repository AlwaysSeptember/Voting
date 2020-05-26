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
use Params\ProcessRule\ValidDatetime;
use Params\Create\CreateFromJson;
use Params\Create\CreateFromArray;

/**
 * A motion that has been proposed to be voted on a later date.
 *
 * They can contain multiple questions.
 *
 * @Entity @Table(name="adminuser") @HasLifecycleCallbacks
 *
 */
class VotingMotion implements InputParameterList
{
    use ToArray;
    use CreateFromArray;
    use CreateFromJson;

    /** @Column(type="string") **/
    private string $id;

    /** @Column(type="string") **/
    private string $type;

    /** @Column(type="string") **/
    private string $name;

    /** @Column(type="datetime") */
    private \DateTimeInterface $start_datetime;

    /** @Column(type="datetime") */
    private \DateTimeInterface $close_datetime;

    /** @Column(type="datetime") @GeneratedValue * */
    protected $created_at;

    /** @Column(type="datetime") @GeneratedValue * */
    protected $updated_at;

    /**
     * @var ProposedQuestion[]
     */
    private array $questions;

    /**
     *
     * @param string $type
     * @param string $name
     * @param \DateTimeInterface $start_datetime
     * @param \DateTimeInterface $close_datetime
     * @param ProposedQuestion[] $questions
     */
    public function __construct(
        string $id,
        string $type,
        string $name,
        \DateTimeInterface $start_datetime,
        \DateTimeInterface $close_datetime,
        $questions
    ) {
        $this->id = $id;
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
     * @return \DateTimeInterface
     */
    public function getStartDatetime(): \DateTimeInterface
    {
        return $this->start_datetime;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCloseDatetime(): \DateTimeInterface
    {
        return $this->close_datetime;
    }

    /**
     * @return ProposedQuestion[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * @return \Params\InputParameter[]
     */
    public static function getInputParameterList(): array
    {
        return [
            new InputParameter(
                'type',
                new GetString(),
                new MinLength(4),
                new MaxLength(2048)
            ),
            new InputParameter(
                'name',
                new GetString(),
                new MinLength(4),
                new MaxLength(256)
            ),
            new InputParameter(
                'start_datetime',
                new GetString(),
                new ValidDatetime()
            ),
            new InputParameter(
                'close_datetime',
                new GetString(),
                new ValidDatetime()
            ),
            new InputParameter(
                'questions',
                new GetArrayOfType(ProposedQuestion::class)
            ),
        ];
    }
}
