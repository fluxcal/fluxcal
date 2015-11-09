<?php

namespace FluxCal\Model;

use DateTime;
use DateInterval;

/**
 * Class Event
 *
 * @author Timon F <dev@timonf.de>
 */
class Event implements EventInterface
{
    /**
     * @var string
     */
    protected $uniqueIdentifier;

    /**
     * @var \DateTime
     */
    protected $dateTimeStart;

    /**
     * @var \DateTime
     */
    protected $dateTimeEnd;

    /**
     * @var int|DateInterval
     */
    protected $duration;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var string
     */
    protected $description;

    /**
     * {@inheritdoc}
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }

    /**
     * @param string $uniqueIdentifier
     */
    public function setUniqueIdentifier($uniqueIdentifier)
    {
        $this->uniqueIdentifier = $uniqueIdentifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateTimeStart()
    {
        return $this->dateTimeStart;
    }

    /**
     * Setter for dateTime, will stored as dateTimeStart.
     *
     * @param \DateTime $dateTime
     */
    public function setDateTimeStart(\DateTime $dateTime)
    {
        $this->dateTimeStart = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateTimeEnd()
    {
        return $this->dateTimeEnd;
    }

    /**
     * Setter for dateTime, will stored as dateTimeEnd.
     *
     * @param \DateTime $dateTime
     */
    public function setDateTimeEnd(\DateTime $dateTime)
    {
        $this->dateTimeEnd = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param DateInterval|int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }


    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Setter for event's description.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

}