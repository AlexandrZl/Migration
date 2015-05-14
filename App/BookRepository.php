<?php

include_once(realpath(dirname(__FILE__) . '/..') . '/autoload/autoload.php');

class BookRepository
{
    private $path;
    private $xmlObj;
    private $xmlParser;
    private $tagName;
    private $xmlIterator;
    private $count;
    private $manager;

    public function __construct ($path, $tagName)
    {
        $this->path = $path;
        $this->tagName = $tagName;
        $this->xmlParser = new XMLParser();
        $this->getContent();
    }


    public function getContent()
    {
        $path = $this->path;

        $this->xmlObj = $this->xmlParser->parseFile($path);
        $this->xmlObj = $this->xmlObj->xpath($this->tagName);

        $this->xmlIterator = new ArrayIterator($this->xmlObj);
        $this->count = $this->xmlIterator->count();
        $this->manager = new Manager($this->xmlIterator, $this->path, $this->tagName);
    }

    public function setClass($class, $name, $entity)
    {
        return $this->manager->setClass($class, $name, $entity);
    }

    public function apply()
    {
        return $this->manager->start();
    }

}

global $PDO;
$booksRepo = new BookRepository("books.xml", 'book');
$booksRepo->setClass('Author', 'authors', 'author');
$booksRepo->apply();

//$booksRepo->newIterator('authors', 'author');
//$book = $booksRepo->fetchNext('Author');
//$book->getObject();
//$book->apply();
//
//$booksRepo->newIterator();
//$book = $booksRepo->fetchNext();
//$book->getObject();
//$book->apply();