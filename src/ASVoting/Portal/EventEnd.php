<?php

declare(strict_types = 1);

namespace Portal;

class EventEnd
{
    /** @var string */
    private $name;

    /** @var string */
    private $description;

    public function __construct(string $name, ?string $description)
    {
        $this->name = $name;
        if ($description === null) {
            $this->description = $name;
        }
        else {
            $this->description = $description;
        }
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
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
