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
use Params\ProcessRule\LaterThanParam;
use Params\Create\CreateFromJson;
use Params\Create\CreateFromArray;
use Params\ProcessRule\Enum;

/**
 * A motion that has been proposed to be voted on a later date.
 *
 * They can contain multiple questions.
 *
 * @HasLifecycleCallbacks
 *
 */
class VotingMotionOpen implements InputParameterList
{
    use ToArray;
    use CreateFromArray;
    use CreateFromJson;

    const STATE_OPEN = 'open';
    const STATE_CLOSED = 'closed';
    const STATE_CANCELLED = 'cancelled';

    /**
     * @Id
     * @Column(type="guid")
     * @GeneratedValue(strategy="NONE")
     *
     */
    private string $id;

    /** @Column(type="string") **/
    private string $type;

    /** @Column(type="string") **/
    private string $name;

    /** @Column(type="string") **/
    private string $proposed_motion_source;

    /** @Column(type="datetime") */
    private \DateTimeInterface $start_datetime;

    /** @Column(type="datetime") */
    private \DateTimeInterface $close_datetime;

    /**
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
     * @Column(type="datetime")
     */
    protected $updated_at;

    public function __construct(
        string $id,
        string $type,
        string $name,
        string $proposedMotionSource,
        \DateTimeInterface $start_datetime,
        \DateTimeInterface $close_datetime
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->proposed_motion_source = $proposedMotionSource;
        $this->start_datetime = $start_datetime;
        $this->close_datetime = $close_datetime;
    }

    /**
     * @PrePersist
     * @codeCoverageIgnore
     */
    public function doPrePersist()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /**
     * @PreUpdate
     * @codeCoverageIgnore
     */
    public function doStuffOnPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
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
    public function getProposedMotionSource(): string
    {
        return $this->proposed_motion_source;
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

//    public function isOpenForVoting(): bool
//    {
//        return $this->state === self::STATE_OPEN;
//    }

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
                'id',
                new GetString(),
                new MinLength(4),
                new MaxLength(2048)
            ),
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
                'proposed_motion_source',
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
        ];
    }
}