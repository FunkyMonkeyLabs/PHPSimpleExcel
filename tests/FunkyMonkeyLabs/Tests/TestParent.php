<?php

namespace FunkyMonkeyLabs\Tests;

abstract class TestParent extends \PHPUnit_Framework_TestCase
{
    protected function getFixture($fixture)
    {
        return trim(file_get_contents(__DIR__."/../../Resources/DataFixtures/".$fixture.".xml"), "\t\r\n");
    }
}