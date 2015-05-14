<?php

class Manager
{
    private $classes = array();
    private $obj;
    private $i = 0;
    private $xmlIterator;
    private $xmlParser;
    private $path;
    private $tagName;
    private $count = 0;


    public function __construct($obj, $path, $tagName)
    {
        $this->obj = $obj;
        $this->tagName = $tagName;
        $this->path = $path;
        $this->xmlParser = new XMLParser();
    }

    public function setClass($class, $name, $entity)
    {
        $this->classes[$this->i]['class'] = $class;
        $this->classes[$this->i]['name'] = $name;
        $this->classes[$this->i]['entity'] = $entity;
        $this->i++;

        return $this->classes;
    }

    public function start() {
        $this->newIterator();
        $this->apply();
        if ($this->classes) {
            foreach ($this->classes as $class) {
                $this->newIterator($class['name'], $class['entity']);
                $this->apply($class['class']);
            }
        }
    }

    private function apply($class = null)
    {
        for ($i = 0; $i < $this->count; $i++) {
            $book = $this->fetchNext($class);
            $book->getObject();
            $book->apply();
        }
    }

    public function fetchNext($class = null)
    {
        if ($this->xmlIterator->valid()) {
            $class = $class ? $class : 'Book';
            $book = new $class($this->xmlIterator->current());
            $this->xmlIterator->next();

            return $book;
        }
        return 0;
    }

    public function newIterator($names = null, $name = null)
    {
        $this->xmlObj = $this->xmlParser->parseFile($this->path);
        $this->xmlObj = $this->xmlObj->xpath($this->tagName);
        if ($names && $name) {
            $this->xmlIterator = new ArrayIterator($this->xmlObj);
            $this->xmlObj = $this->xmlIterator->current()->$names->xpath($name);
        }
        $this->xmlIterator = new ArrayIterator($this->xmlObj);
        $this->count = $this->xmlIterator->count();
    }

}