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

    public function __construct ($path, $tagName)
    {
        $this->path = $path;
        $this->tagName = $tagName;
        $this->xmlParser = new XMLParser();
        $this->getContent();
    }

    public function fetchNext()
    {
        if ($this->xmlIterator->valid()) {
            $book = new Book($this->xmlIterator->current());
            $this->xmlIterator->next();

            return $book;
        }
        return 0;
    }

    private function getContent()
    {
        $path = $this->path;

        $this->xmlObj = $this->xmlParser->parseFile($path);
        $this->xmlObj = $this->xmlObj->xpath($this->tagName);

        $this->xmlIterator = new ArrayIterator($this->xmlObj);
        $this->count = $this->xmlIterator->count();
    }


    public function apply()
    {
        if ($this->xmlIterator->valid()) {
            for ($i = 0; $i < $this->count; $i++) {
                $book = $this->fetchNext();
                $book->getObject();
                $book->apply();
            }
        }

    }
}

global $PDO;
$booksRepo = new BookRepository("books.xml", 'book');

//$book = $booksRepo->fetchNext();
//$book->getObject();
//$book->apply();
//
//$book = $booksRepo->fetchNext();
//$book->getObject();
//$book->apply();

$booksRepo->apply();
