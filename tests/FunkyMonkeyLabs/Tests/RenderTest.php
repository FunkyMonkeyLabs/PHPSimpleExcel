<?php

namespace FunkyMonkeyLabs\Tests;

use FunkyMonkeyLabs\PHPSimpleExcel;

class RenderTest extends TestParent
{
    public function testAddRow()
    {
        $excel = new PHPSimpleExcel();
        $excel->addRow(array(
                "id", "Column1", "Column2"
            ));
        $this->assertEquals($this->getFixture("simplyRow"), $excel->generateXML());
    }

    public function testAddColumn()
    {
        $excel = new PHPSimpleExcel();
        $excel->startRow()
            ->addCell("test")
            ->end();
        $this->assertEquals($this->getFixture("simplyCell"), $excel->generateXML());
    }
}