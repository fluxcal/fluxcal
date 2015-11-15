<?php

namespace FluxCal\Writer;

use DateTime;
use DateInterval;
use FluxCal\Model\CalendarInterface;
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
    const ICS_DATE_FORMAT = 'Ymd';

    const TYPE_CALENDAR = 'VCALENDAR';
    const TYPE_EVENT = 'VEVENT';

    const DEFAULT_PRODUCT_IDENTIFIER = '-//FluxCal//ICS Writer//EN';

    /**
     * @var CalendarInterface
     */
    protected $calendar;

    /**
     * @var string
     */
    protected $productIdentifier = null;

    /**
     * Sets a custom product identifier
     *
     * @param string $schemaOwner
     * @param string $schemaDescription
     * @param string $schemaLanguage
     */
    public function setProductIdentifier($schemaOwner, $schemaDescription, $schemaLanguage = 'en')
    {
        $this->productIdentifier = sprintf(
            '-//%s//%s//%s',
            $schemaOwner,
            $schemaDescription,
            strtoupper($schemaLanguage)
        );
    }

    /**
     * Returns product identifier
     *
     * @return string
     */
    public function getProductIdentifier()
    {
        if ($this->productIdentifier === null) {
            return static::DEFAULT_PRODUCT_IDENTIFIER;
        }

        return $this->productIdentifier;
    }

    public function resetProductIdentifier()
    {
        $this->productIdentifier = null;
    }

    /**
     * Returns a single VCALENDAR attribute.
     *
     * @param string $name
     * @param string $value
     *
     * @return string
     */
    public function writeAttribute($name, $value, $escapeSpecialChars = true)
    {
        if ($escapeSpecialChars === true) {
            $value = static::escapeString($value);
        }

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
     */
    public function writeEvent(EventInterface $event)
    {
        $iCal = $this->writeOpenSection(self::TYPE_EVENT);

        if ($event->getUniqueIdentifier()){
            $iCal .= $this->writeAttribute('uid', $event->getUniqueIdentifier());
        }

        $iCal .= $this->writeAttribute('summary', $event->getSummary());
        $iCal .= $this->writeAttribute('description', $event->getDescription());

        if ($event->getDateTimeStart() instanceof DateTime) {
            if (intval($event->getDateTimeStart()->format('His')) == 0) {
                $iCal .= $this->writeAttribute('dtstart', $event->getDateTimeStart()->format(self::ICS_DATE_FORMAT));
            } else {
                $iCal .= $this->writeAttribute('dtstart', $event->getDateTimeStart()->format(self::ICS_DATETIME_FORMAT));
            }
        }

        if ($event->getDateTimeEnd() instanceof DateTime) {
            if (intval($event->getDateTimeEnd()->format('His')) == 0) {
                $iCal .= $this->writeAttribute('dtend', $event->getDateTimeEnd()->format(self::ICS_DATE_FORMAT));
            } else {
                $iCal .= $this->writeAttribute('dtend', $event->getDateTimeEnd()->format(self::ICS_DATETIME_FORMAT));
            }
        }

        if ($event->getDuration() instanceof DateInterval) {
            $iCal .= $this->writeAttribute('duration', $this->formatInterval($event->getDuration()));
        } elseif(is_numeric($event->getDuration())) {
            $iCal .= $this->writeAttribute('duration', sprintf('P%sS', (int)$event->getDuration()));
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
        $iCal .= $this->writeAttribute('prodid', $this->getProductIdentifier());
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

    /**
     * Escapes a string
     *
     * @param string $value
     * @return string
     */
    protected static function escapeString($value)
    {
        return str_replace([';', ','], ['\\;', '\\,'], $value);
    }

}