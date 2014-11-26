<?php

namespace FluxCal\Writer;

use FluxCal\Model\CalendarInterface;

/**
 * Class IcsWriter
 *
 * @author Timon F <dev@timonf.de>
 */
class IcsWriter implements WriterInterface, CalendarAwareInterface
{
    const NEW_LINE = 0x0D0A; // CR+LF, by RFC 5545 3.1

    const LINE_LENGTH = 75;

    /**
     * Returns a single VCALENDAR attribute.
     *
     * @param string $name
     * @param string $value
     *
     * @return string
     * @author Timon F <dev@timonf.de>
     */
    public function writeAttribute($name, $value)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function setCalendar(CalendarInterface $calendar)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function write()
    {
        return '';
    }
}