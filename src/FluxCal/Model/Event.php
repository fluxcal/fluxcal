<?php

namespace FluxCal\Model;

/**
 * Class Event
 *
 * @author Timon F <dev@timonf.de>
 */
class Event
{
    /**
     * @var \DateTime
     */
    protected $endDateTime;

    /**
     * @var string
     */
    protected $description;

    /**
     * Setter for dateTime, will stored as endDateTime.
     *
     * @param \DateTime $dateTime
     *
     * @author Timon F <dev@timonf.de>
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->endDateTime = $dateTime;
    }

    /**
     * Getter for dateTime, will read from endDateTime.
     *
     * @return \DateTime
     *
     * @author Timon F <dev@timonf.de>
     */
    public function getDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * Setter for event's description.
     *
     * @param string $description
     *
     * @author Timon F <dev@timonf.de>
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Getter for event's description.
     *
     * @return string
     *
     * @author Timon F <dev@timonf.de>
     */
    public function getDescription()
    {
        return $this->description;
    }
}