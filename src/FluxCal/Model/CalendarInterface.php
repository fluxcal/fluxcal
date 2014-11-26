<?php

namespace FluxCal\Model;

/**
 * Interface CalendarInterface
 *
 * @author Timon F <dev@timonf.de>
 */
interface CalendarInterface
{
    /**
     * Return all stored events.
     *
     * @return EventInterface[]
     * @author Timon F <dev@timonf.de>
     */
    public function getEvents();
}