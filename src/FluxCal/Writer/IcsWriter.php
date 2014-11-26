<?php

namespace FluxCal\Writer;

use FluxCal\Model\CalendarInterface;
use FluxCal\Model\Event;
use FluxCal\Model\EventInterface;

/**
 * Class IcsWriter
 *
 * @author Timon F <dev@timonf.de>
 */
class IcsWriter implements WriterInterface, CalendarAwareInterface
{
    const NEW_LINE = "\r\n"; // CR+LF, by RFC 5545 3.1

    const LINE_LENGTH = 75;

    const VERSION = '2.0';

    const TYPE_CALENDAR = 'VCALENDAR';
    const TYPE_EVENT = 'VCALENDAR';

    /**
     * @var CalendarInterface
     */
    protected $calendar;

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
        $attribute = strtoupper($name) . ':' . $value;
        $result    = '';
        $indent    = 0;

        while(substr($attribute, (self::LINE_LENGTH + $indent) * -1) != '') {
            if ($indent == 1) {
                $attribute = ' ' . $attribute;
            }

            $result   .= substr($attribute, 0, (self::LINE_LENGTH));
            $attribute = substr($attribute, (self::LINE_LENGTH));

            $indent = 1;
            $result .= self::NEW_LINE;
        }

        return $result;
    }

    /**
     * Opens a section of a component (something like BEGIN:VEVENT)
     *
     * @param string $component
     *
     * @return string
     * @author Timon F <dev@timonf.de>
     */
    public function writeOpenSection($component)
    {
        return $this->writeAttribute('begin', strtoupper($component));
    }

    /**
     * Closes a section of a component (something like BEGIN:VEVENT)
     *
     * @param string $component
     *
     * @return string
     * @author Timon F <dev@timonf.de>
     */
    public function writeCloseSection($component)
    {
        return $this->writeAttribute('end', strtoupper($component));
    }


    /**
     * {@inheritdoc}
     */
    public function setCalendar(CalendarInterface $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * Writes events (@todo: don't forget other types like journal)
     *
     * @return string
     * @author Timon F <dev@timonf.de>
     */
    public function writeEvents()
    {
        $iCal = '';
        foreach($this->calendar->getEvents() as $event) {
            $iCal .= $this->writeEvent($event);
        }
        return $iCal;
    }

    /**
     * Writes a single event.
     *
     * @param EventInterface $event
     *
     * @return string
     * @author Timon F <dev@timonf.de>
     */
    public function writeEvent(EventInterface $event)
    {
        $iCal = $this->writeOpenSection(self::TYPE_EVENT);
        if ($event instanceof Event) {
            $iCal .= $this->writeAttribute('description', $event->getDescription());
            // todo: date time conversion (\DateTime to ics/rfc standard)
        }
        $iCal .= $this->writeCloseSection(self::TYPE_EVENT);
        return $iCal;
    }

    /**
     * {@inheritdoc}
     */
    public function write()
    {
        $iCal = $this->writeOpenSection(static::TYPE_CALENDAR);
        $iCal .= $this->writeAttribute('version', static::VERSION);
        $iCal .= $this->writeEvents();
        $iCal .= $this->writeCloseSection(static::TYPE_CALENDAR);

        return $iCal;
    }
}