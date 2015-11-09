<?php

namespace FluxCal\Model;

use Iterator;

/**
 * Class Calendar
 *
 * @author Timon F <dev@timonf.de>
 */
class Calendar implements CalendarInterface, Iterator
{
    protected $position = 0;

    /**
     * @var EventInterface[]
     */
    protected $events = [];

    /**
     * Adds an event of which implements EventInterface.
     *
     * @param EventInterface $event
     */
    public function addEvent(EventInterface $event)
    {
        $this->events[] = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Remove all events.
     */
    public function removeEvents()
    {
        $this->events = [];
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     *
     * @return Event
     */
    public function current()
    {
        $this->events[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return array_key_exists($this->position, $this->events);
    }
}