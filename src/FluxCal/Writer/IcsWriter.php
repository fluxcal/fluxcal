<?php

namespace FluxCal\Writer;

use DateTime;
use DateInterval;
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

    const INTERVAL_ISO8601 = 'P%yY%mM%dDT%hH%iM%sS';
    const ICS_DATETIME_FORMAT = 'Ymd\THis';

    const TYPE_CALENDAR = 'VCALENDAR';
    const TYPE_EVENT = 'VEVENT';

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
            $iCal .= $this->writeAttribute('summary', $event->getSummary());

            if ($event->getDateTimeStart() instanceof DateTime) {
                $iCal .= $this->writeAttribute('dtstart', $event->getDateTimeEnd()->format(self::ICS_DATETIME_FORMAT));
            }

            if ($event->getDateTimeEnd() instanceof DateTime) {
                $iCal .= $this->writeAttribute('dtend', $event->getDateTimeStart()->format(self::ICS_DATETIME_FORMAT));
            }

            if ($event->getDuration() instanceof DateInterval) {
                $iCal .= $this->writeAttribute('duration', $this->formatInterval($event->getDuration()));
            } elseif(intval($event->getDuration())) {
                $iCal .= $this->writeAttribute('duration', sprintf('P%sS', (int)$event->getDuration()));
            }
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

    /**
     * Formating the interval like ISO 8601 (PnYnMnDTnHnMnS)
     *
     * @param DateInterval $dateInterval
     *
     * @return string
     */
    protected static function formatInterval(DateInterval $dateInterval)
    {
        $sReturn = 'P';

        if($dateInterval->y){
            $sReturn .= $dateInterval->y . 'Y';
        }

        if($dateInterval->m){
            $sReturn .= $dateInterval->m . 'M';
        }

        if($dateInterval->d){
            $sReturn .= $dateInterval->d . 'D';
        }

        if($dateInterval->h || $dateInterval->i || $dateInterval->s){
            $sReturn .= 'T';

            if($dateInterval->h){
                $sReturn .= $dateInterval->h . 'H';
            }

            if($dateInterval->i){
                $sReturn .= $dateInterval->i . 'M';
            }

            if($dateInterval->s){
                $sReturn .= $dateInterval->s . 'S';
            }
        }

        return $sReturn;
    }

}