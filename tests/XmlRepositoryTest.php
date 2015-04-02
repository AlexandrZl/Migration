<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.03.15
 * Time: 15:37
 */

require_once(realpath(dirname(__FILE__) . '/..') . '/App/BookRepository.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/Book.php');

$PDO = require_once(realpath(dirname(__FILE__) . '/..') . '/autoload/database.php');

class XmlRepositoryTest extends PHPUnit_Framework_TestCase {

    protected $dir = '';
    protected $pdo;

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

        global $CONFIG;

        $dsn = "mysql:dbname={$CONFIG['db_name']};host={$CONFIG['db_host']}";
        $pdo = new PDO($dsn, $CONFIG['db_user'], $CONFIG['db_pass'], array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo = $pdo;
    }

    public function testBookReps() {
        $book_repository = new BookRepository($this->dir . "books.xml", "book");

        $book1 = $book_repository->fetchNext();
        $book2 = $book_repository->fetchNext();

        $this->assertEquals('Book', get_class($book1));
        $this->assertEquals('10', $book1->bid);
        $this->assertEquals('War and peace', $book1->booktitle);
        $this->assertEquals('11', $book2->bid);
        $this->assertEquals('Anna Karenina', $book2->booktitle);
        $this->assertEquals(NULL, $book1->book);

        $book_repository->push($this->pdo);

        $sql = $this->pdo->prepare("SELECT id, title FROM book WHERE id = '10'");
        $sql->execute();
        $row = $sql->fetch();

        $this->assertEquals('War and peace', $row['title']);
    }


    public function tearDown() {
        unlink($this->dir."books.xml");
        $this->pdo->exec("DROP TABLE book ");
    }

}