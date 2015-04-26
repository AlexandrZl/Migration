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
        $this->count = $this->xmlIterator->count();
    }


    public function apply()
    {
        for ($i=0; $i < $this->count; $i++)
        {
            $book = $this->fetchNext();
            $book->getObject();
            $book->apply();
        }

    }
}

global $PDO;
$booksRepo = new BookRepository("books.xml", 'book');
$booksRepo->apply();
//$book = $booksRepo->fetchNext();
//$book->getObject();
//$book->apply();

//$book = $booksRepo->fetchNext();
//$book->getObject();
//$book->apply();

//$booksRepo->push($PDO);

//$sth = $PDO->prepare("SELECT id, title FROM book WHERE id = '10'");
//$sth->execute();
//$test = $sth->fetch();
//var_dump($test['title']);


//$book = $booksRepo->fetchNext();
//$book1 = $booksRepo->fetchNext();
//
//echo $book->bid;








