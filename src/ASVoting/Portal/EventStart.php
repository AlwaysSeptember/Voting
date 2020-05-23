<?php

declare(strict_types = 1);

namespace Portal;

class EventStart
{
    /** @var string */
    private $name;

    /** @var string|null */
    private $description;

    /** @var EventEnd[] */
    private $endEvents;

    /**
     *
     * @param string $name
     * @param string|null $description
     * @param EventEnd[] $endEvents
     */
    public function __construct(string $name, ?string $description, array $endEvents)
    {
        $this->name = $name;
        $this->description = $description;
        $this->endEvents = $endEvents;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return EventEnd[]
     */
    public function getEndEvents(): array
    {
        return $this->endEvents;
    }

    public function getEndEventClassname(EventEnd $endEvent)
    {
        return $this->getName() . '_' . $endEvent->getName();
    }
}
