<?php

namespace FunkyMonkeyLabs\Tests\Element;

use FunkyMonkeyLabs\Element\Row;
use FunkyMonkeyLabs\PHPSimpleExcel;
use FunkyMonkeyLabs\Tests\TestParent;

class RowTest extends TestParent
{
    public function testRowWithoutGuessingType()
    {
        $row = new Row('UTF-8');
        $row->addCell('1')
            ->end();

        $this->assertEquals($this->getFixture('singleRowWithoutGuessing'), $row->render());
    }

    public function testRowWithGuessing()
    {
        $row = new Row('UTF-8', true);
        $row->addCell('1')
            ->end();

        $this->assertEquals($this->getFixture('singleRowWithGuessing'), $row->render());
    }
}