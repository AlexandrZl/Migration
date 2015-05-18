<?php

class Manager
{
    private $classes = array();
    private $obj;
    private $i = 0;
    private $xmlIterator;
    private $entityIterator;
    private $entityCount = 0;
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
        if ($this->classes) {
            foreach ($this->classes as $class) {
                $this->newIterator($class['name'], $class['entity']);
                $this->apply($class['class']);
                for ($i = 1; $i < $this->entityCount; $i++) {
                    $this->entityIterator->next();
                    $xmlObj = $this->entityIterator->current()->$class['name']->xpath($class['entity']);
                    $this->xmlIterator = new ArrayIterator($xmlObj);
                    $this->count = $this->xmlIterator->count();
                    $this->apply($class['class']);
                }
            }
        }
        $this->newIterator();
        $this->apply();
    }

    public function applyByClass($byClass)
    {
        $findClass = false;
        foreach ($this->classes as $class) {
            if ($class['class'] == $byClass) {
                $findClass = true;
                $this->newIterator($class['name'], $class['entity']);
                $this->apply($class['class']);
                for ($i = 1; $i < $this->entityCount; $i++) {
                    $this->entityIterator->next();
                    $xmlObj = $this->entityIterator->current()->$class['name']->xpath($class['entity']);
                    $this->xmlIterator = new ArrayIterator($xmlObj);
                    $this->count = $this->xmlIterator->count();
                    $this->apply($class['class']);
                }
            }
        }
        if (!$findClass) {
            $this->newIterator();
            $this->apply();
        }
    }

    private function apply($class = null)
    {
        for ($i = 0; $i < $this->count; $i++) {
            $book = $this->fetchNext($class);
            $book->getObject();
            $book->apply();
            $book->applyEntity();
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
        $xmlObj = $this->xmlParser->parseFile($this->path);
        $xmlObj = $xmlObj->xpath($this->tagName);
        if ($names && $name) {
            $this->entityIterator = new ArrayIterator($xmlObj);
            $this->entityCount = $this->entityIterator->count();
            $xmlObj = $this->entityIterator->current()->$names->xpath($name);
        }
        $this->xmlIterator = new ArrayIterator($xmlObj);
        $this->count = $this->xmlIterator->count();
    }

}