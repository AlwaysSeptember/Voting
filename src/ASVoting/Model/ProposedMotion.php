<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\App;
use ASVoting\ToArray;
use Params\Create\CreateOrErrorFromArray;
use Params\ExtractRule\GetArrayOfType;
use Params\ExtractRule\GetDatetime;
use Params\ExtractRule\GetString;
use Params\InputParameter;
use Params\InputParameterList;
use Params\ProcessRule\LaterThanParam;
use Params\ProcessRule\MaxLength;
use Params\ProcessRule\MinLength;
use Params\ProcessRule\ValidDatetime;
use Params\Create\CreateFromJson;
use Params\Create\CreateFromArray;

/**
 * A motion that has been proposed to be voted on a later date.
 *
 * They can contain multiple questions.
 */
class ProposedMotion implements InputParameterList
{
    use ToArray;
    use CreateFromArray;
    use CreateOrErrorFromArray;
    use CreateFromJson;

    private string $type;

    private string $name;

    /** @Column(type="string") **/
    private string $source;


    private \DateTimeInterface $start_datetime;

    private \DateTimeInterface $close_datetime;

    /**
     * @var ProposedQuestion[]
     */
    private array $questions;

    public function __construct(
        string $type,
        string $name,
        string $source,
        \DateTimeInterface $start_datetime,
        \DateTimeInterface $close_datetime,
        $questions
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->source = $source;

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
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
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
        $allowedFormats = [
            App::MYSQL_DATETIME_FORMAT,
            \DateTime::ATOM,
            \DateTime::COOKIE,
            \DateTime::ISO8601,
            \DateTime::RFC822,
            \DateTime::RFC850,
            \DateTime::RFC1036,
            \DateTime::RFC1123,
            \DateTime::RFC2822,
            \DateTime::RFC3339,
            \DateTime::RFC3339_EXTENDED,
            \DateTime::RFC7231,
            \DateTime::RSS,
            \DateTime::W3C,
        ];

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
                'source',
                new GetString(),
                new MinLength(4),
                new MaxLength(256)
            ),
            new InputParameter(
                'start_datetime',
                new GetDatetime($allowedFormats)
            ),
            new InputParameter(
                'close_datetime',
                new GetDatetime($allowedFormats),
                new LaterThanParam('start_datetime', 60)
            ),
            new InputParameter(
                'questions',
                new GetArrayOfType(ProposedQuestion::class)
            ),
        ];
    }
}
