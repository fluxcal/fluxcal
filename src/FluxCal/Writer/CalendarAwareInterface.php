<?php

namespace FluxCal\Writer;

use FluxCal\Model\CalendarInterface;

/**
 * Interface CalendarAwareInterface
 *
 * @author Timon F <dev@timonf.de>
 */
interface CalendarAwareInterface
{
    /**
     * For writers who are able to use the Calendar model.
     *
     * @param CalendarInterface $calendar
     *
     * @return string
     * @author Timon F <dev@timonf.de>
     */
    public function setCalendar(CalendarInterface $calendar);
}