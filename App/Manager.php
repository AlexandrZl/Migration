<?php

include_once(realpath(dirname(__FILE__) . '/..') . '/autoload/autoload.php');

class Manager
{
    private $path;
    private $xmlObj;
    private $xmlParser;
    private $classes = array();
    private $i = 0;

    public function __construct ($path)
    {
        $this->path = $path;
        $this->xmlParser = new XMLParser();
        $this->getContent();
    }

    public function getContent()
    {
        $this->xmlObj = $this->xmlParser->parseFile($this->path);
    }

    public function setClass($class, $name)
    {
        $this->classes[$this->i]['class'] = $class;
        $this->classes[$this->i]['name'] = $name;
        $this->i++;

        return $this->classes;
    }

    public function saveAll()
    {
        if ($this->classes) {
            foreach ($this->classes as $class) {
                $obj = $this->xmlObj->xpath('//'.$class['name']);
                $repository = new Repository($class['class'], $class['name'], $obj);
                $repository->start();
            }
        }
    }

}

global $PDO;
$manager = new Manager("books.xml");
$manager->setClass('Book', 'book');
$manager->setClass('Author', 'author');
$manager->setClass('Article', 'article');
$manager->saveAll();