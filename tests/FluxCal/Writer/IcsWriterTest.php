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
        $output = $this->icsWriter->write();

        $this->assertNotEmpty($output);
    }

}