<?php

use FluxCal\Model\Event;

/**
 * Class EventTest
 *
 * @author Timon F <dev@timonf.de>
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testDescriptionAttribute()
    {
        $event = new Event();
        $event->setDescription('foobar');
        $this->assertEquals('foobar', $event->getDescription());
    }
}