<?php

declare(strict_types = 1);

namespace ASVoting\Model;

use ASVoting\ToArray;
use Params\ExtractRule\GetString;
use Params\InputParameter;
use Params\InputParameterList;
use Params\ProcessRule\MinLength;
use Params\ProcessRule\MaxLength;

/**
 * A choice that is available for a question.
 *
 * @Entity
 * @Table(name="voting_choice")
 * @HasLifecycleCallbacks
 */
class VotingChoice implements InputParameterList
{
    use ToArray;

    /** @Column(type="string", name="id") @GeneratedValue **/
    private string $id;

    /**
     * @Column(type="string")
     */
    private string $text;

    /**
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
     * @Column(type="datetime")
     */
    protected $updated_at;

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
     *
     * @param string $id
     * @param string $text
     */
    public function __construct(string $id, string $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId(): string
    {
        return $this->id;
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
                'id',
                new GetString(),
                new MinLength(4),
                new MaxLength(2048)
            ),
            new InputParameter(
                'text',
                new GetString(),
                new MinLength(1),
                new MaxLength(2048)
            )
        ];
    }
}
