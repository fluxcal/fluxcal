<?php

namespace FluxCal\Model;

/**
 * Class Calendar
 *
 * @author Timon F <dev@timonf.de>
 */
class Calendar implements CalendarInterface
{
    /**
     * @var EventInterface[]
     */
    protected $events = [];

    /**
     * Adds an event of which implements EventInterface.
     *
     * @param EventInterface $event
     *
     * @author Timon F <dev@timonf.de>
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
     *
     * @author Timon F <dev@timonf.de>
     */
    public function removeEvents()
    {
        $this->events = [];
    }
}