<?php

require_once(realpath(dirname(__FILE__) . '/..') . '/App/Book.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/XMLParser.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/TemplatedSQL.php');

$PDO = require_once(realpath(dirname(__FILE__) . '/..') . '/autoload/database.php');

class MigrationTest extends PHPUnit_Framework_TestCase
{
    public $path;

    public function setUp()
    {
        $xml = '<book>tests</book>';

        for ($i=0;$i<5;$i++) {
            $directory = md5($i);
            $this->path[] = $directory;
            if (!file_exists('tests/'.$directory)) {
                mkdir('tests/'.$directory, 0777, true);
            }
            $file = fopen('tests/'.$directory."/example.xml", "w");

            fwrite($file, $xml);
            fclose($file);
        }
    }

    public function testParseXml()
    {
        $file = new XMLParser();

        foreach ($this->path as $path) {
            $content = $file->parseFile("tests/".$path."/example.xml");
            $this->assertEquals('tests', $content[0]);
        }
    }

    public function tearDown()
    {
        foreach ($this->path as $path) {
            foreach (glob("tests/{$path}/*") as $file)
            {
                if(!is_dir($file)) {
                    unlink($file);
                }
            }
            rmdir("tests/".$path);
        }
    }

}