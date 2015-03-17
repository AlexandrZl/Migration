<?php

class MigrationTest extends PHPUnit_Framework_TestCase
{

    public function testParseXml()
    {
        $pathToFile = 'tests/example.xml';
        $file = new XMLParser();
        $content = $file->parseFile($pathToFile);

        $this->assertEquals('tests', $content[0]);
    }

}