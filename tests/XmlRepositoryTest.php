<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.03.15
 * Time: 15:37
 */

require_once 'App/App/BookRepository.php';

class XmlRepositoryTest extends PHPUnit_Framework_TestCase {

    protected $dir = '';

    public function setUp() {
        $content = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<books>
  <book id="10">
    <title>War and peace</title>
  </book>
  <book id="11">
    <title>Anna Karenina</title>
  </book>
</books>
EOL;

        $this->dir = "/tmp/" . md5(time());
        file_put_contents($this->dir . "books.xml", $content);
    }

    public function testBookReps() {
        $book_repository = new BookRepository($this->dir . "books.xml");
        $book1 = $book_repository->fetchNext();
        $book2 = $book_repository->fetchNext();
        $this->assertEquals('Book', get_class($book1));
        $this->assertEquals('10', $book1->bid);
        $this->assertEquals('War and peace', $book1->booktitle);
        $this->assertEquals('11', $book2->bid);
        $this->assertEquals('Anna Karenina', $book2->booktitle);

        $this->assertEquals(NULL, $book1->book);
    }


    public function tearDown() {
        unlink($this->dir."books.xml");
    }

}