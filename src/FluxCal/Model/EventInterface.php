<?php

namespace FluxCal\Model;

use DateTime;
use DateInterval;

/**
 * Interface EventInterface
 *
 * @author Timon F <dev@timonf.de>
 */
interface EventInterface
{
    /**
     * Returns an unique identifier for an event
     *
     * @return mixed
     */
    public function getUniqueIdentifier();

    /**
     * Returns end of the event
     *
     * @return DateTime
     */
    public function getDateTimeEnd();

    /**
     * Returns begin of the event
     *
     * @return DateTime
     */
    public function getDateTimeStart();

    /**
     * Short long or short description of the event (optional)
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Short title or summary of the event (optional)
     *
     * @return string|null
     */
    public function getSummary();

    /**
     * Use a DateInterval instance or use seconds instead
     *
     * @return int|DateInterval
     */
    public function getDuration();
}