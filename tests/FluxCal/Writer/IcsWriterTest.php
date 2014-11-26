<?php

use FluxCal\Writer\IcsWriter;

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
        $this->assertContains('bar', $line);
    }

    public function testWriteIsNotEmpty()
    {
        $output = $this->icsWriter->write();

        $this->assertNotEmpty($output);
    }

}