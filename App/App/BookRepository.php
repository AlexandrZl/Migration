<?php

require_once "Book.php";
require_once "XMLParser.php";

class BookRepository
{
    private $path;
    private $xmlObj;
    private $xmlParser;
    private $tagName = "book";
    private $xmlIterator;

    public function __construct ($path)
    {
        $this->path = $path;
        $this->xmlParser = new XMLParser();
        $this->getContent();
    }

    public function fetchNext()
    {
        $book = new Book($this->xmlIterator->current());
        $this->xmlIterator->next();

        return $book;
    }

    private function getContent()
    {
        $path = $this->path;

        $this->xmlObj = $this->xmlParser->parseFile($path);
        $this->xmlObj = $this->xmlObj->xpath($this->tagName);

        $this->xmlIterator = new ArrayIterator($this->xmlObj);
    }
}

//$booksRepo = new BookRepository("books.xml");
//$book = $booksRepo->fetchNext();
////$book1 = $booksRepo->fetchNext();
//
//echo $book->bid;








