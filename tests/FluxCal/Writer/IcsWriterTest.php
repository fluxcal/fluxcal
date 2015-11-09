<?php

use FluxCal\Writer\IcsWriter;
use FluxCal\Model\Calendar;
use FluxCal\Model\Event;

/**
 * Class IcsWriterTest
 *
 * @author Timon F <dev@timonf.de>
 */
class IcsWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IcsWriter
     */
    private $icsWriter;

    public function setUp()
    {
        $this->icsWriter = new IcsWriter();
    }

    public function testWriteAttributeResult()
    {
        $line = $this->icsWriter->writeAttribute('foo', 'bar');

        $this->assertContains('FOO', $line);
        $this->assertNotContains('foo', $line);
        $this->assertContains('bar', $line);
    }

    public function testWriteAttributeUsingLongValues()
    {
        $longText = 'First' . str_pad('', 160, 'Test ') . ' Last';
        $line = $this->icsWriter->writeAttribute('foo', $longText);
        $lines = explode(IcsWriter::NEW_LINE, $line);

        $this->assertCount(4, $lines); // 3 (from 160/75), and one at the end.
        $this->assertContains('Test', $line);
        $this->assertContains('First', $line);
        $this->assertContains('Last', $line);
    }

    public function testWriteIsNotEmpty()
    {
        $this->icsWriter->setCalendar($this->getDemoCalendar());
        $output = $this->icsWriter->write();

        $this->assertNotEmpty($output);
    }

    /**
     * Generates an example calendar with a single test event
     *
     * @return Calendar
     */
    protected function getDemoCalendar()
    {
        $event = new Event();
        $event->setDateTimeEnd(new DateTime('2016/01/01 00:00:00'));
        $event->setDateTimeStart(new DateTime('2016/01/02 00:00:00'));
        $event->setDuration(24 * 60 * 60);
        $event->setDescription('Test Description');
        $event->setSummary('Test Title');

        $calendar = new Calendar();
        $calendar->addEvent($event);

        return $calendar;
    }

}